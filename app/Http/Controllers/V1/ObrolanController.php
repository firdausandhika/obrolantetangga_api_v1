<?php

namespace App\Http\Controllers\V1;

use JWTAuth;
use App\Model\Obrolan;
use App\Model\ObrolanView;
use App\Model\ModelTmpObrolan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\V1\V1Controller;
use Storage;
use App\Model\ObrolanGambar;

class ObrolanController extends V1Controller
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
    public function index(Request $request)
    {
      return $this->obrolan($request);
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
          $wilayah = $this->user->kelurahan;
          if ($request->wilayah != null) {
            $wilayah = $request->wilayah;
          }

          try {
            $obrolan = Obrolan::create([
              'kategori_id'=>$request->kategori_id,
              'user_id'=>$this->user->id,
              'wilayah'=>$wilayah,
              'unik'=> Str::random(20),
              'kontent'=>\nl2br(htmlspecialchars($request->kontent)),
              'poin'=>0,
            ]);
          } catch (\Exception $e) {
            return $e;
          }

          $name = array();
          $name_file = "";

          if ($files = $request->file('gambar')) {
            foreach ($files as $file) {
              try {
                  $extension = $file->extension();
                  $name_file = $this->user->unik_user . Str::random(5) . '.' . $extension;
                  Storage::putFileAs('public/obrolan', $file, $name_file);

                  // proses kompresi
                  $syntax = [
                    "python3",
                    "/www/wwwroot/obrolantetangga.com/storage/app/png_jpg.py",
                    "/www/wwwroot/obrolantetangga.com/storage/app/public/obrolan/".$name_file
                  ];

                  // $process = new Process($syntax);
                  // $process->run();

                  // if (!$process->isSuccessful()) {
                  //   // permiison
                  //       return ProcessFailedException($process);
                  //   }

                  $name[] = $name_file;

                  ObrolanGambar::create([
                    'obrolan_id' => $obrolan->id,
                    'gambar' => $name_file,
                  ]);
                } catch (\Exception $e) {
                  return $e;
                }
              }
            }

          $this->res->msg   = "Success";
          $this->res->data  =  ['obrolan'=>$obrolan];
          return \response()->json($this->res);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Obrolan  $obrolan
     * @return \Illuminate\Http\Response
     */
    public function show($unik,Request $request)
    {
      $obrolan =  Obrolan::whereUnik($unik)
      ->with('kategori')
      ->with('obrolan_gambar')
      ->with('user')
      ->orderBy('created_at', 'DESC')
      ->first();

      if (!$obrolan) {
        $this->res->success =  false;
        $this->res->msg =  "Data Not Found";
        return \response()->json($this->res,404);
      }
      $obrolan->append('is_like')->append('is_dislike');
      $this->res->data =  $obrolan;
      return \response()->json($this->res);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Obrolan  $obrolan
     * @return \Illuminate\Http\Response
     */
    public function edit(Obrolan $obrolan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Obrolan  $obrolan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$unik)
    {
        // return $request;
        $obrolan =  Obrolan::whereUnik($unik)
        ->with('kategori')
        ->with('obrolan_gambar')
        ->with('user')
        ->orderBy('created_at', 'DESC')
        ->first();

        if (!$obrolan) {
          $this->res->success =  false;
          $this->res->msg =  "Data Not Found";
          return \response()->json($this->res,404);
        }

        $obrolan->update([
          'kategori_id'=>$request->kategori_id,
          'kontent'=>\nl2br(htmlspecialchars($request->kontent)),
        ]);

        $this->res->data =  $obrolan;
        return \response()->json($this->res);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Obrolan  $obrolan
     * @return \Illuminate\Http\Response
     */
    public function destroy($unik)
    {
        $obrolan =  Obrolan::whereUnik($unik)->first();

        if (!$obrolan) {
          $this->res->success =  false;
          $this->res->msg =  "Data Not Found";
          return \response()->json($this->res,404);
        }

        $this->res->msg =  "Success";
        $obrolan->delete();
        return \response()->json($this->res);
    }

    public function obrolan(Request $request,$next_tokens=null)
    {
      // return \response()->json();
      $next_token = $request->next_token;
      if ($next_token == null) {
        $next_token =  $this->user->unik_user."_".Str::random(10);
      }

      if ($next_tokens) {
        $next_token = $next_tokens;
      }

      $query = Obrolan::query()->filter($request)
      ->whereNotIn('id', function($q)
      {
         $q->from('reports')->whereNull('deleted_at')
          ->select('obrolan_id')
          ->where('user_id', auth()->user()->id)->whereNull('kategori_postingan');
      })
      ->whereNotIn('id', function($q) use($next_token)
      {
         $q->from('model_tmp_obrolans')->whereNull('deleted_at')
          ->select('obrolan_id')
          ->where('user_id', $this->user->id)->whereNextToken($next_token);
      })
      ->with('kategori')
      ->with('obrolan_gambar')
      ->with('user')
      ->orderBy('created_at', 'DESC')
      ->where("wilayah", "like", "%{$this->user->kota}%")
      ->limit(5);

      $obrolans =  $query->get();

      $this->view($obrolans,$this->user,$next_token);
      $obrolans->each(function ($items) {
          // $items->append('provinsi_data');
          // $items->append('kota_data');
          // $items->append('kecamatan_data');
          // $items->append('kelurahan_data');
          // $items->append('total_view');
          // $items->append('current_user');
          $items->append('is_like');
          $items->append('is_dislike');
          $items->append('media');
         });
      $this->res->data =  ['obrolans'=>$obrolans,'next_token'=>$next_token];

      return \response()->json($this->res);
    }

    public function view($obrolans,$user,$next_token=null)
    {
      foreach($obrolans as $obrolan){
        if ($obrolan->user_id != $user->id) {
          ObrolanView::firstOrCreate([
            'obrolan_id'=>$obrolan->id,
            'user_id'=>$user->id
          ]);
        }

        if ($next_token) {
          ModelTmpObrolan::firstOrCreate([
            'obrolan_id'=>$obrolan->id,
            'user_id'=>$user->id,
            'next_token'=>$next_token
          ]);
        }
      }
    }
}
