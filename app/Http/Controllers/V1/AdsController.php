<?php

namespace App\Http\Controllers\V1;


use App\Model\IklanBannerLetak;
use App\Model\IklanDefault;
use Illuminate\Http\Request;
use App\Http\Controllers\V1\V1Controller;

class AdsController extends V1Controller
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

        // $iklan_default_mobile = IklanDefault::where([
        //     'letak' => 'Trending',
        //     'device' => 'Mobile'
        // ])->first();
            
        $this->res->msg   = "Success";
        $iklans = IklanBannerLetak::whereHas('iklanbanner', function ($q){
            $q->where('wilayah', '64.72');
        });
        $this->res->data = 1;
        // ->where('tanggal_awal', '<=', date("Y-m-d"))->where('tanggal_akhir', '>=', date("Y-m-d"))->first()
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
