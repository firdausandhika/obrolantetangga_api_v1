<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\V1\V1Controller;
use Illuminate\Http\Request;
use App\Model\IklanReports;
use App\Model\IklanBaris;

class ReportIklanBarisController extends V1Controller
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
         // return $request;
         $user      = $this->user;
         $iklan_baris   = IklanBaris::whereUnik($request->iklan_baris_unik)->first();

         if (!$iklan_baris) {
           $this->res->success  =  false;
           $this->res->msg      =  "Data Not Found";
           return \response()->json($this->res,404);
         }
         // return $iklan_baris;

         $report = IklanReports::create([
           "user_id"=>$user->id,
           "iklan_baris_id"=>$iklan_baris->id,
           "kategori_iklan_baris"=>$request->kategori_postingan,
           "kategori_pelanggaran"=>$request->kategori_pelanggaran,
           "target_id"=>$iklan_baris->user_id
         ]);

         $data = array(
           'target_post_card' => $request->kategori_postingan == null ? $request->target_post_card : '',
           'target_post_card_focus' =>$request->target_post_card
         );

         $count = 0;
         if ($request->kategori_postingan !=  null) {
           $count = IklanReports::whereIklanBarisId($iklan_baris->id)->whereKategoriPostingan($request->kategori_postingan)->count();
         }

         if ($count > 2) {
           $iklan_baris->update(["kategori_id"=>$request->kategori_postingan]);
         }

         $this->res->data = ['iklan_baris'=>$iklan_baris,'report'=>$report];
         return \response()->json($this->res);
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
