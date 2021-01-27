<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\KategoriReport as KategoriPelanggaran;
use App\Http\Controllers\V1\V1Controller;

class KategoriReportController extends V1Controller
{
  public function __construct(Request $request)
  {
      $this->user = $this->user();
      $this->res  = $this->res();
      $this->res->request = $request->except('_token');
  }

  public function index()
  {
    $this->res->data =  KategoriPelanggaran::all();
    return \response()->json($this->res);
  }
}
