<?php

namespace App\Http\Controllers\V1;

use App\Model\Wilayah;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\V1\V1Controller;
use App\Models\Gcp;
use App\Model\User;
use Storage;

class WilayahController extends V1Controller
{
  public function __construct(Request $request)
  {
      $this->user = $this->user();
      $this->res  = $this->res();
      $this->res->request = $request->except('_token');
  }

  public function get_provinsi()
  {

    // $disk = Storage::disk('gcs');
    //
    //   $directory = "alfin_new";
    //   if (!file_exists($disk->path($directory))) {
    //     $disk->makeDirectory($directory);
    //   }
    //   $disk->put($directory.'/alfin1.txt', 'alfin');

    $this->res->data =  Wilayah::whereRaw("CHAR_LENGTH(kode)=2")
    ->orderBy("nama")->orderBy('nama','asc')->get();
    return \response()->json($this->res);
  }

  public function get_kota($id)
  {
    $this->res->data = Wilayah::whereRaw("LEFT(kode,2)='{$id}'")
    ->whereRaw("CHAR_LENGTH(kode)=5")
    ->orderBy("nama")->orderBy('nama','asc')->get();
    return \response()->json($this->res);
  }

  public function get_kecamatan($id)
  {
    $this->res->data = Wilayah::whereRaw("LEFT(kode,5)='{$id}'")
    ->whereRaw("CHAR_LENGTH(kode)=8")
    ->orderBy("nama")->orderBy('nama','asc')->get();
    return \response()->json($this->res);
  }

  public function get_kelurahan($id)
  {
    $this->res->data = Wilayah::whereRaw("LEFT(kode,8)='{$id}'")
    ->whereRaw("CHAR_LENGTH(kode)=13")
    ->orderBy("nama")->orderBy('nama','asc')->get();
    return \response()->json($this->res);
  }

  public function copyToGoogle()
  {
    // return $als = User::select('cover','avatar','id')
    // ->whereNotNull('avatar')
    // ->whereNotNull('cover')
    // ->get()->toJson();
    // /*
    $profils = \json_decode('[{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/alfin\/alfin.png","avatar":"avatar\/alfin21_40_15_03.png","id":1},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/gKuVopHhBW\/gKuVopHhBW.png","avatar":"avatar\/gKuVopHhBW20_44_23_10.png","id":2},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/hTNXrpdyRn\/hTNXrpdyRn.png","avatar":"avatar\/hTNXrpdyRn20_19_20_10.png","id":3},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/wYPg5A4rxX\/wYPg5A4rxX.png","avatar":"avatar\/wYPg5A4rxX20_36_23_10.png","id":4},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/mA6cbp4Iep\/mA6cbp4Iep.png","avatar":"avatar\/mA6cbp4Iep20_38_20_10.png","id":5},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/knAV5Vfflz\/knAV5Vfflz.png","avatar":"avatar\/knAV5Vfflz20_50_20_10.png","id":6},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/216yybW8Kf\/216yybW8Kf.png","avatar":"avatar\/216yybW8Kf20_08_20_10.png","id":7},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/tA3tawnbhM\/tA3tawnbhM.png","avatar":"avatar\/tA3tawnbhM20_48_20_10.png","id":9},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/Zd7SZmWljN\/Zd7SZmWljN.png","avatar":"avatar\/Zd7SZmWljN21_52_09_01.png","id":10},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/WGia3Tv3Ye\/WGia3Tv3Ye.png","avatar":"avatar\/WGia3Tv3Ye20_48_21_10.png","id":14},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/mSzrgMOaf2\/mSzrgMOaf2.png","avatar":"avatar\/mSzrgMOaf220_09_20_10.png","id":19},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/sabqI3TvnS\/sabqI3TvnS.png","avatar":"avatar\/sabqI3TvnS20_39_20_10.png","id":22},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/WkPMnAcyOV\/WkPMnAcyOV.png","avatar":"avatar\/WkPMnAcyOV20_01_24_10.png","id":23},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/7F6n47AJOF\/7F6n47AJOF.png","avatar":"avatar\/7F6n47AJOF20_38_20_10.png","id":29},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/nGy0Rl2x9x\/nGy0Rl2x9x.png","avatar":"avatar\/nGy0Rl2x9x20_56_20_10.png","id":31},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/lVijBsLy5K\/lVijBsLy5K.png","avatar":"avatar\/lVijBsLy5K20_19_20_10.png","id":33},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/lMqFEyCyg6\/lMqFEyCyg6.png","avatar":"avatar\/lMqFEyCyg620_04_20_10.png","id":34},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/zCZbNPM0ni\/zCZbNPM0ni.png","avatar":"avatar\/zCZbNPM0ni20_18_20_10.png","id":35},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/5nbpEmr1ns\/5nbpEmr1ns.png","avatar":"avatar\/5nbpEmr1ns20_33_28_10.png","id":42},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/nTSbbWc70v\/nTSbbWc70v.png","avatar":"avatar\/nTSbbWc70v21_15_01_04.png","id":51},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/loWJ8lYuyE\/loWJ8lYuyE.png","avatar":"avatar\/loWJ8lYuyE21_41_22_02.png","id":88},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/5fpKiYSnb7\/5fpKiYSnb7.png","avatar":"avatar\/5fpKiYSnb721_34_01_03.png","id":93},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/iSbZJ3PTVw\/iSbZJ3PTVw.png","avatar":"avatar\/iSbZJ3PTVw21_33_02_03.png","id":94},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/7MOt1gxTb9\/7MOt1gxTb9.png","avatar":"avatar\/7MOt1gxTb921_24_02_03.png","id":95},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/8ipeys5JGi\/8ipeys5JGi.png","avatar":"avatar\/8ipeys5JGi21_31_02_03.png","id":96},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/yAPQ9lkPSe\/yAPQ9lkPSe.png","avatar":"avatar\/yAPQ9lkPSe21_56_15_03.png","id":102},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/RoSM8Alcd0\/RoSM8Alcd0.png","avatar":"avatar\/RoSM8Alcd021_08_15_03.png","id":103},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/wnMmLIvtfC\/wnMmLIvtfC.png","avatar":"avatar\/wnMmLIvtfC21_40_20_03.png","id":105},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/jkm3OLr97x\/jkm3OLr97x.png","avatar":"avatar\/jkm3OLr97x21_47_24_04.png","id":119},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/cover\/Odzfa0mVh3\/Odzfa0mVh3.png","avatar":"avatar\/Odzfa0mVh321_50_24_04.png","id":125}]');

    $error = [];
    foreach ($profils as $key => $profil) {
      try {
        $xxuser = User::find($profil->id)
        if ($xxuser) {
          $xxuser->update(['cover'=>$profil->cover,'avatar'=>$profil->avatar]);
        }else {
          $error[] = $profil;
        }

      } catch (\Exception $e) {
        $error[] = $e->getMessage();
      }

    }

    return $error;

    // return User::all();

    //*/

    // unik_user
    // avatar

    // $disk = Storage::disk('gcs');
    // $data = [];
    // //
    //   // $directory = "alfin_new";
    //   // return Storage::disk('gcs')->url('alfin.txt');
    //
    // $url = "https://storage.googleapis.com/obrolantetangga";
    // $users =  User::whereNotNull('cover')->get();
    //
    // if (!file_exists($disk->path("cover"))) {
    //   $disk->makeDirectory("cover");
    // }
    //
    // foreach ($users as $key => $user) {
    //   if (!file_exists($disk->path("cover/{$user->unik_user}"))) {
    //     $disk->makeDirectory("cover/{$user->unik_user}");
    //   }
    //
    //   try {
    //     Storage::disk('gcs')->put("cover/{$user->unik_user}/{$user->unik_user}.png", Storage::disk('public')->get($user->cover));
    //     $user->update(['cover'=>"{$url}/cover/{$user->unik_user}/{$user->unik_user}.png"]);
    //     ['error'=>null,'user'=>$user,'cover'=>"{$url}/cover/{$user->unik_user}/{$user->unik_user}.png"];
    //   } catch (\Exception $e) {
    //     $data[] = ['error'=>$e->getMessage(),'user'=>$user,'cover'=>"{$url}/cover/{$user->unik_user}/{$user->unik_user}.png"];
    //   }
    //
    //   // code...
    // }
    // return $data;
  }
}
