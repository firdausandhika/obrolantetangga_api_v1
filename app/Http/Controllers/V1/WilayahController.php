<?php

namespace App\Http\Controllers\V1;

use App\Model\Wilayah;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\V1\V1Controller;
use App\Models\Gcp;
use App\Model\User;
use App\Model\Obrolan;
use App\Model\ObrolanGambar;
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


}
