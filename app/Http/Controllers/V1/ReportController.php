<?php

namespace App\Http\Controllers\V1;

use App\Model\Report;
use App\Model\Obrolan;

use Storage;
use \Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Controllers\V1\V1Controller;


class ReportController extends V1Controller
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
         $user      = auth()->user();
         $obrolan   = Obrolan::whereUnik($request->obrolan_unik)->first();

         if (!$obrolan) {
           $this->res->success  =  false;
           $this->res->msg      =  "Data Not Found";
           return \response()->json($this->res,404);
         }
         // return $obrolan;

         $report = Report::create([
           "user_id"=>$user->id,
           "obrolan_id"=>$obrolan->id,
           "kategori_postingan"=>$request->kategori_postingan,
           "kategori_pelanggaran"=>$request->kategori_pelanggaran,
           "target_id"=>$obrolan->user_id
         ]);

         $data = array(
           'target_post_card' => $request->kategori_postingan == null ? $request->target_post_card : '',
           'target_post_card_focus' =>$request->target_post_card
         );

         $count = 0;
         if ($request->kategori_postingan !=  null) {
           $count = Report::whereObrolanId($obrolan->id)->whereKategoriPostingan($request->kategori_postingan)->count();
         }

         if ($count > 2) {
           $obrolan->update(["kategori_id"=>$request->kategori_postingan]);
         }

         $this->res->data = ['obrolan'=>$obrolan,'report'=>$report];
         return \response()->json($this->res);
     }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }
}
