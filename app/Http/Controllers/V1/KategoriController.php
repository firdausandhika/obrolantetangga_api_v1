<?php

namespace App\Http\Controllers\V1;

use App\Model\Wilayah;
use App\Model\Kategori;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\V1\V1Controller;

class KategoriController extends V1Controller
{
  public function __construct(Request $request)
  {
      $this->user = $this->user();
      $this->res  = $this->res();
      $this->res->request = $request->except('_token');
  }

  public function index(Request $request)
  {
    $jenis_id = 1;

    if ($request->jenis_id) {
      $jenis_id = $request->jenis_id;
    }
    $this->res->data =  Kategori::whereJenisId($jenis_id)->get();
    return \response()->json($this->res);
  }


}
