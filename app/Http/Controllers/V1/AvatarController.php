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

use Storage;
use \Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Controllers\V1\V1Controller;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AvatarController extends V1Controller
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
    public function index()
    {
        //
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
       // return \response()->json($this->res,404);
       // return $this->res;
       return $this->upload_avatar($request);
     }

     public function change_avatar($imageName,$request)
     {
       try {
         $user = User::whereId(auth()->user()->id)->first();

         $auto_post = false;

         if ($user->avatar == null) {
           $auto_post = true;
         }
         $user->update(['avatar'=>"https://storage.googleapis.com/obrolantetangga/".$imageName]);
       } catch (\Exception $e) {
         return array('success'=>$e);
       }

       if ($auto_post) {
         try {
           $this->new_member_posting($this->user);
         } catch (\Exception $e) {
            return array('success'=>$e);
         }
       }


         return array('success'=>'true','user'=>User::find($this->user->id),'msg'=>'Success');

     }

     public function upload_avatar($request)
     {
       $image = $request->gambar; // image base64 encoded

       try {
         $pos  = strpos($image, ';');
         $image_extension = explode(':', substr($image, 0, $pos))[1];

         preg_match("/data:image\/(.*?);/",$image,$image_extension); // extract the image extension
         $image = preg_replace('/data:image\/(.*?);base64,/','',$image); // remove the type part
         $image = str_replace(' ', '+', $image);

         if (!in_array(Str::lower($image_extension[1]),['png','jpg','jpeg'])) {
           // return redirect()->back()->with('danger', );
           return array('success'=>'Gagal upload foto, format Tidak Sesuai');
         }


         $imageName = "{$this->user->unik_user}/avatar/".auth()->user()->unik_user.Carbon::now()->format('y_s_d_m').'.'.$image_extension[1];
         // Storage::disk('public')->put($imageName, base64_decode($image));

           Storage::disk('gcs')->put($imageName,base64_decode($image));



       } catch (\Exception $e) {
         return array('success'=>$e->getMessage());
       }




       return $this->change_avatar($imageName,$request);
     }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function new_member_posting($user)
    {
      Obrolan::create(
        [
          'kontent'=> "Apa kabar warga (OT) ObrolanTetangga. Saya warga baru dari kelurahan <b>{$user->_kelurahan->nama}</b>",
          'kategori_id'=>10,
          'user_id'=>$user->id,
          'wilayah'=>$user->kelurahan,
          'unik'=> Str::random(20),
          'current_wilayah_user'=>$user->kelurahan,
        ]
      );
    }
}
