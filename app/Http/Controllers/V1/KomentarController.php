<?php
namespace App\Http\Controllers\V1;

use App\Model\User;
use App\Model\Notif;
use App\Model\Obrolan;
use App\Model\ObrolanLike;
use App\Model\ObrolanPoin;
use App\Model\ObrolanView;
use App\Model\ObrolanDislike;
use App\Model\ObrolanKomentar;
use App\Model\ModelTmpKomentar;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Controllers\V1\V1Controller;


class KomentarController extends V1Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('jwt.verify');
        $this->user = $this->user();
        $this->res  = $this->res();
        $this->res->request = $request->except('_token');
        $this->res->ip = $request->ip();
        $this->res->user_agen = $request->header('User-Agent');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      // $obrolan_unik = "SCp5jZyJrtZGQJdZ1nbm";
      $obrolan_unik = $request->obrolan_unik;
      if ($obrolan_unik == null) {
        // $obrolan_unik =  $this->user->unik_user."_".Str::random(10);
      }

      $next_token = $request->next_token;
      if ($next_token == null) {
        $next_token =  $this->user->unik_user."_".Str::random(10);
      }

      // return $next_token;
      $obrolan = Obrolan::whereUnik($obrolan_unik)->first();

      if (!$obrolan) {
        $this->res->success =  false;
        $this->res->msg =  "Data Not Found";
        return \response()->json($this->res,404);
      }

      $komentars =  ObrolanKomentar::whereObrolanId($obrolan->id)
      ->whereNotIn('id', function($q) use($next_token)
      {
         $q->from('model_tmp_komentars')->whereNull('deleted_at')
          ->select('komentar_id')
          ->where('user_id', $this->user->id)->whereNextToken($next_token);
      })
      ->whereNull('parent_id')
      ->with('user')
      ->with('sub_komen.user')
      ->limit(10)
      ->get();

      $this->view($komentars,$this->user,$next_token);

      return \response()->json($this->res);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function store(Request $request)
     {
       if (!$request->komentar) {
         $this->res->success =  false;
         $this->res->msg =  "Komentar Tidak Boleh Kosong";
         return \response()->json($this->res,404);
       }

       $obrolan = Obrolan::whereUnik($request->obrolan_unik)->first();

       if (!$obrolan) {
         $this->res->success =  false;
         $this->res->msg =  "Data Not Found";
         return \response()->json($this->res,404);
       }


       $user = $this->user;

       $obrolan_komentar = ObrolanKomentar::create([
         "obrolan_id"  =>$obrolan->id,
         "komentar"    =>\nl2br(htmlspecialchars($request->komentar)),
         "user_id"     =>$user->id,
         "parent_id"   =>$request->parent_komentar_unik,
         'unik'        => $request->obrolan_unik.'_'.Str::random(20),
       ]);

       ObrolanPoin::create(['obrolan_id'=>$obrolan->id, 'user_id'=>$user->id,'point'=>1,'model_name'=>'ObrolanKomentar','model_id'=>$obrolan_komentar->id]);
       $obrolan->increment('poin');

       $ok =  ObrolanKomentar::with('user')->find($obrolan_komentar->id);

       if (($request->parent_komentar_unik == null) && ($user->id != $obrolan->user_id)) {
         $this->makeNotif($obrolan,$request);
       }

       $count_dislike  = ObrolanDislike::whereObrolanId($obrolan->id)->count();
       $count_like     = ObrolanLike::whereObrolanId($obrolan->id)->count();
       $count_view     = ObrolanView::whereObrolanId($obrolan->id)->count();
       $count_comment  = ObrolanKomentar::whereNull('parent_id')->whereObrolanId($obrolan->id)->count();

       $obrolan->update([
         "count_dislike" => $count_dislike,
         "count_like"    => $count_like,
         "view"          => $count_view,
         "count_comment" => $count_comment,
       ]);

       $this->res->data = ['komentar'=>$obrolan_komentar];
       return \response()->json($this->res);

       // return response()->json($data);
       // return $obrolan_komentar;
     }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\ObrolanKomentar  $obrolanKomentar
     * @return \Illuminate\Http\Response
     */
    public function show(ObrolanKomentar $obrolanKomentar,$unik)
    {
      $obrolan_komentar = ObrolanKomentar::whereUnik($unik)->first();

      if (!$obrolan_komentar) {
        $this->res->success =  false;
        $this->res->msg =  "Data Not Found";
        return \response()->json($this->res,404);
      }


      $this->res->data = ['komentar'=>$obrolan_komentar];
      return \response()->json($this->res);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\ObrolanKomentar  $obrolanKomentar
     * @return \Illuminate\Http\Response
     */
    public function edit(ObrolanKomentar $obrolanKomentar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\ObrolanKomentar  $obrolanKomentar
     * @return \Illuminate\Http\Response
     */
    public function update($unik,Request $request)
    {
      if (!$request->komentar) {
        $this->res->success =  false;
        $this->res->msg =  "Komentar Tidak Boleh Kosong";
        return \response()->json($this->res,404);
      }

      $obrolan_komentar = ObrolanKomentar::whereUnik($unik)->first();

      if (!$obrolan_komentar) {
        $this->res->success =  false;
        $this->res->msg =  "Data Not Found";
        return \response()->json($this->res,404);
      }

      $obrolan_komentar->update([
        "komentar"    =>\nl2br(htmlspecialchars($request->komentar)),
      ]);

      $this->res->data = ['komentar'=>$obrolan_komentar];
      return \response()->json($this->res);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\ObrolanKomentar  $obrolanKomentar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$unik)
    {
      $obrolan_komentar = ObrolanKomentar::whereUnik($unik)->first();

      if (!$obrolan_komentar) {
        $this->res->success =  false;
        $this->res->msg =  "Data Not Found";
        return \response()->json($this->res,404);
      }

      $obrolan = Obrolan::whereId($obrolan_komentar->obrolan_id)->first();

      if (!$obrolan) {
        $this->res->success =  false;
        $this->res->msg =  "Data Not Found";
        return \response()->json($this->res,404);
      }

      $obrolan_komentar->delete();
      $obrolan->decrement('poin');
      $this->res->msg = "Berhasil Dihapus";
      return \response()->json($this->res);
    }

    public function view($komentars,$user,$next_token=null)
    {
      foreach($komentars as $komentar){
        if ($komentar->user_id != $user->id) {
          // ObrolanView::firstOrCreate([
          //   'komentar_id'=>$komentar->id,
          //   'user_id'=>$user->id
          // ]);
        }

        if ($next_token) {
          ModelTmpKomentar::firstOrCreate([
            'komentar_id'=>$komentar->id,
            'user_id'=>$user->id,
            'next_token'=>$next_token
          ]);
        }
      }

      $this->res->data = $komentars;
    }

    public function makeNotif($obrolan,$request)
    {
      $user   = $this->user;
      $oks    = ObrolanKomentar::select('user_id')->with('user')->whereObrolanId($obrolan->id)->groupBy('user_id')->get();
      $this->res->oks = $oks;
      $nama   = '';

      if ($oks->count() > 2) {
        $iter = 0;
        foreach ($oks as $key => $ok) {
          $iter++;
          $nama .= $ok->user->nama;

          if ($iter == 2) {
            break;
          }
          $nama .=', ';
        }

        $nama .=  " dan ".($oks->count()-2);

      }else if ($oks->count() == 2) {
        $iter = 0;
        foreach ($oks as $key => $ok) {
          $iter++;
          $nama .= $ok->user->nama;

          if ($iter == 1) {
            $nama .=' dan ';
          }

        }


      }else{
        $nama .=  $oks->first()->user->nama;
      }

      $notif  =   Notif::firstOrCreate(['obrolan_id'=>$obrolan->id]);
      $notif->update([
        'user_from'=>$user->id,
        'user_to' =>$obrolan->user_id,
        'nama_notif'=>$nama,
        'deskripsi'=>Str::limit(\nl2br(htmlspecialchars($request->komentar)),20),
        'status'=>1,
      ]);
    }
}
