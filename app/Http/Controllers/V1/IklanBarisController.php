<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\V1\V1Controller;
use Illuminate\Http\Request;
use App\Model\IklanBaris;
use App\Model\IklanReport;
use Illuminate\Support\Str;
use App\Model\ModelTmpIklanBaris;

class IklanBarisController extends V1Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('jwt.verify');
        $this->user = $this->user();
        $this->res  = $this->res();
        $this->res->request = $request->except('_token');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$next_tokens=null)
    {
      $next_token = $request->next_token;
      if ($next_token == null) {
        $next_token =  $this->user->unik_user."_".Str::random(10);
      }

      $query = IklanBaris::query()->filter($request)
      ->whereNotIn('id', function($q)
      {
         $q->from('iklan_reports')->whereNull('deleted_at')
          ->select('iklan_baris_id')
          ->where('user_id', auth()->user()->id)->whereNull('kategori_iklan_baris');
      })
      ->whereNotIn('id', function($q) use($next_token)
      {
         $q->from('model_tmp_iklan_baris')->whereNull('deleted_at')
          ->select('iklan_baris_id')
          ->where('user_id', $this->user->id)->whereNextToken($next_token);
      })
      ->with('kategori')
      ->with('user')
      ->orderBy('created_at', 'DESC')
      ->where("wilayah", "like", "%{$this->user->kota}%")
      ->limit(5);

      if ($request->kategori_id != null) {
          try {
            $query->where('kategori_id',$request->kategori_id);
          } catch (\Exception $e) {
            // return $e;
          }
        }

       $iklan_baris =  $query->get();

        $this->view($iklan_baris,$this->user,$next_token);

        $this->res->data =  ['iklan_baris'=>$iklan_baris,'next_token'=>$next_token];

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
      if (request()->get('kontent') == '') {
        $this->res->msg = "Kontent Tidak Boleh Kosong";
        $this->res->success = false;
        return \response()->json($this->res);
      }


          try {
            $is_send_wa = 0;

            if(($request->is_send_wa == 'false') or ($request->is_send_wa == false) or ($request->is_send_wa == 0)){
              $is_send_wa = 0;
            }

            if(($request->is_send_wa == 'true') or ($request->is_send_wa == true) or ($request->is_send_wa == 1)){
              $is_send_wa = 1;
            }
            $iklan_baris = IklanBaris::create([
              'kategori_id'=>$request->kategori_id,
              'user_id'=>$this->user->id,
              'wilayah'=>$this->user->kelurahan,
              'unik'=> Str::random(20),
              'number_wa'=>  $is_send_wa ? $request->no_wa : null,
              'kontent'=>\nl2br(htmlspecialchars($request->kontent)),
            ]);
          } catch (\Exception $e) {
            // return $e;
            $this->res->msg = $e;
            $this->res->success = false;
            return \response()->json($this->res);
          }

          $this->res->msg   = "Success";
          $this->res->data  =  ['user'=>$this->user,'iklan_baris'=>IklanBaris::with('kategori')->findOrFail($iklan_baris->id)];
          return \response()->json($this->res);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function show($unik,Request $request)
     {
       $obrolan =  IklanBaris::whereUnik($unik)
       ->with('kategori')
       ->with('user')
       ->orderBy('created_at', 'DESC')
       ->first();

       if (!$obrolan) {
         $this->res->success =  false;
         $this->res->msg =  "Data Not Found";
         return \response()->json($this->res,404);
       }
       $this->res->data =  $obrolan;
       return \response()->json($this->res);
     }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $unik)
    {
      $iklan_baris =  IklanBaris::whereUnik($unik)
      ->with('user')
      ->orderBy('created_at', 'DESC')
      ->first();

      if (!$iklan_baris) {
        $this->res->success =  false;
        $this->res->msg =  "Data Not Found";
        return \response()->json($this->res,404);
      }

      $iklan_baris->update([
        'kategori_id'=>$request->kategori_id,
        'kontent'=>\nl2br(htmlspecialchars($request->kontent)),
      ]);

      $this->res->data  =  IklanBaris::whereUnik($unik)
        ->with('kategori')
        ->with('user')
        ->orderBy('created_at', 'DESC')
        ->first();
      return \response()->json($this->res);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($unik)
    {
      $iklan_baris =  IklanBaris::whereUnik($unik)->first();

      if (!$iklan_baris) {
        $this->res->success =  false;
        $this->res->msg =  "Data Not Found";
        return \response()->json($this->res,404);
      }

      $this->res->msg =  "Success";
      $iklan_baris->delete();
      return \response()->json($this->res);
    }

    public function view($iklan_bariss,$user,$next_token=null)
    {
      foreach($iklan_bariss as $iklan_baris){
        if ($next_token) {
          ModelTmpIklanBaris::firstOrCreate([
            'iklan_baris_id'=>$iklan_baris->id,
            'user_id'=>$user->id,
            'next_token'=>$next_token
          ]);
        }
      }
    }
}
