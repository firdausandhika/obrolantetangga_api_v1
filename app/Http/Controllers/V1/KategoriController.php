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

  public function index()
  {
    $this->res->data =  Kategori::whereJenisId(1)->get();
    return \response()->json($this->res);
  }


}
