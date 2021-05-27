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

        $iklan_default_mobile = IklanDefault::whereDevice('Mobile')->get();
        // $this->res->data = ['iklans'=> $iklan_default_mobile];
        $data[0]['letak'] = 'trending';
        $data[0]['image'] = '';

        $data[1]['letak'] = 'iklan_baris';
        $data[1]['image'] = '';
        foreach ($iklan_default_mobile as $key => $value) {
            if($value->letak == 'Trending'){
                $data[0]['image'] = $value->foto_iklan;
            }

            if($value->letak == 'IklanBaris'){
                $data[1]['image'] = "https://obrolantetangga.com/".$value->foto_iklan;
            }
        }

        $this->res->msg   = "Success";
        $iklans = IklanBannerLetak::whereHas('iklanbanner', function ($q) {
            $q->where('wilayah', $this->user->kota);
        })->where('tanggal_awal', '<=', date("Y-m-d"))->where('tanggal_akhir', '>=', date("Y-m-d"))->get();

        foreach ($iklans as $key => $value) {
            if($value->letak == 'Trending'){
                $data[0]['image'] = $value->foto_iklan_mobile;
            }

            if($value->letak == 'IklanBaris'){
                $data[1]['image'] = $value->foto_iklan_mobile;
            }

        }
        $this->res->data = ['iklans'=>$data];
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
