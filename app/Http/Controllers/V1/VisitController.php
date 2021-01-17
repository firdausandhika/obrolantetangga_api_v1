<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\V1\V1Controller;;
use Illuminate\Http\Request;
use App\Model\Obrolan;
use Illuminate\Support\Str;

use App\Http\Controllers\V1\ObrolanController;

class VisitController extends V1Controller
{

  public function __construct(Request $request,ObrolanController $obrolanController)
  {
      $this->middleware('jwt.verify');
      $this->user = $this->user();
      $this->res  = $this->res();
      $this->res->request = $request->except('_token');
      $this->res->ip = $request->ip();
      $this->res->user_agen = $request->header('User-Agent');
      $this->obv = $obrolanController;
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function index(Request $request)
     {
       $kode_kota = $request->kode_kota;
       if ($kode_kota == null) {
         $this->res->success = false;
         $this->res->msg = "kode_kota tidak tidak boleh kosong";
         return \response()->json($this->res);
       }


       $next_token = $request->next_token;
       if ($next_token == null) {
         $next_token =  $this->user->unik_user."_".Str::random(10);
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
       ->where("wilayah", "like", "%{$kode_kota}%")
       ->limit(5);

       $obrolans =  $query->get();

       $this->obv->view($obrolans,$this->user,$next_token);
       $obrolans->each(function ($items) {
           $items->append('is_like');
           $items->append('is_dislike');
           $items->append('media');
          });
       $this->res->data =  ['obrolans'=>$obrolans,'next_token'=>$next_token];

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
       $kode_kota = $request->kode_kota;
       if ($kode_kota == null) {
         $this->res->success = false;
         $this->res->msg = "kode_kota tidak tidak boleh kosong";
         return \response()->json($this->res);
       }

           try {
             $obrolan = Obrolan::create([
               'kategori_id'=>$request->kategori_id,
               'user_id'=>$this->user->id,
               'wilayah'=>$kode_kota,
               'unik'=> Str::random(20),
               'kontent'=>\nl2br(htmlspecialchars($request->kontent)),
               'poin'=>0,
               'current_wilayah_user'=>$this->user->kelurahan,
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
                   $syntax0 = [
                     "cp",
                     "/www/wwwroot/api.obrolantetangga.com/storage/app/public/obrolan/".$name_file,
                     "/www/wwwroot/obrolantetangga.com/storage/app/public/obrolan/"
                   ];

                   $process0 = new Process($syntax0);
                   $process0->run();

                   // proses kompresi
                   $syntax = [
                     "python3",
                     "/www/wwwroot/obrolantetangga.com/storage/app/png_jpg.py",
                     "/www/wwwroot/obrolantetangga.com/storage/app/public/obrolan/".$name_file
                   ];

                   $process = new Process($syntax);
                   $process->run();


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


             if ($files_video = $request->file('video')) {
               foreach ($files_video as $file) {
                 try {
                     $extension = $file->extension();
                     $name_file = $this->user->unik_user . Str::random(5) . '.' . $extension;
                     Storage::putFileAs('public/obrolan', $file, $name_file);

                     // proses kompresi
                     $syntax0 = [
                       "cp",
                       "/www/wwwroot/api.obrolantetangga.com/storage/app/public/obrolan/".$name_file,
                       "/www/wwwroot/obrolantetangga.com/storage/app/public/obrolan/"
                     ];

                     $process0 = new Process($syntax0);
                     $process0->run();

                     //

                     // if (!$process->isSuccessful()) {
                     //   // permiison
                     //       return ProcessFailedException($process);
                     //   }

                     $name[] = $name_file;

                     ObrolanVideo::create([
                       'obrolan_id' => $obrolan->id,
                       'video' => $name_file,
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
     * @param  int  $id
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
     * @param  int  $id
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
}
