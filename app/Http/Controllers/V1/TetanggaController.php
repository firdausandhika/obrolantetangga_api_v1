<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\V1\V1Controller;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Wilayah;

class TetanggaController extends V1Controller
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
      $query1   = User::query()->where('id','!=',$this->user->id);
      $query    = User::query()->where('id','!=',$this->user->id)->orderBy('id','desc');
      $query->whereActive(1);
      $query->whereNotNull('avatar');

      $query1->whereActive(1);
      $query1->whereNotNull('avatar');
      $kota  = $this->user->_kota->nama;

      if ($request->kota) {
        $query->whereKota($request->kota);
        $query1->whereKota($request->kota);
        $q_kota = Wilayah::whereKode($request->kota)->first();

        if ($q_kota) {
          $kota = $q_kota->nama;
        }

      }else{
        $query->whereKota($this->user->kota);
        $query1->whereKota($this->user->kota);
      }
      $tetanggas = $query->paginate(20);
      $n_tetangga = $query->count();

      $this->res->msg   = "Success";
      $this->res->data   = ["tetanggas"=>"1","jumlah_tetangga"=>"1"];
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
        //
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
}
