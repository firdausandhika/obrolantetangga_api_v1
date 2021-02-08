<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FirebaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $token = "fhiPh-JJ3XRZVg3mddLz4X:APA91bH6LjTusfJzK1jNK5ql9BbhSSkMoiTSnHzgQdHk5OAeLFbQemQae09ihQ60lYX1rUI0R3nDy5tvRrOPH4Mu4tPY9l-D-s0MrjuSYEu00KiKZmsw31LWU1oQgnzZ5UIoDDjzmzoM";
      $from = "AAAAsiUyiZA:APA91bFXq0HxEzw_wQHw01tCKLNp29k7OAd6C2249inRIing5HTkfBsFqUg4JVaoqEs0oTFL4fTFURRmPwmrCskZELaYZzPxqgaF2KpmP1gv_ni5ecYvt68u_bh3w7AjdPAsQvH8S0eX";
        $msg = array
              (
                'body'  => "Alfin Telah Mengomentari Status Anda",
                'title' => "Obrolan Tetangga",
              );

        $fields = array
                (
                    'to'        => $token,
                    'notification'  => $msg
                );

        $headers = array
                (
                    'Authorization: key=' . $from,
                    'Content-Type: application/json'
                );
        //#Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        dd($result);
        curl_close( $ch );
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
