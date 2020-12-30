<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\V1\V1Controller;

use App\Model\User;
use App\Model\Obrolan;
use App\Model\ObrolanView;
use App\Model\ObrolanLike;
use App\Model\ObrolanPoin;
use App\Model\ObrolanDislike;
use App\Model\ObrolanKomentar;

class DislikeController extends V1Controller
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
       $user = $this->user;
       if (!$request->obrolan_unik) {
         $this->res->success =  false;
         $this->res->msg =  "Obrolan Unik Tidak Boleh Kosong";
         return \response()->json($this->res,404);
       }

       $obrolan = Obrolan::whereUnik($request->obrolan_unik)->first();

       if (!$obrolan) {
         $this->res->success =  false;
         $this->res->msg =  "Obrolan Tidak Ditemukan";
         return \response()->json($this->res,404);
       }



        $like_ob_like = ObrolanLike::whereObrolanId($obrolan->id)->whereUserId($user->id)->first();

        if ($like_ob_like) {
          $like_ob_like->delete();
          try {
            User::find($obrolan->user_id)->decrement('count_like');
            $obrolan->decrement('poin',3);
          } catch (\Exception $e) {

          }

        }

        ObrolanPoin::whereModelName("ObrolanLike")->whereObrolanId($obrolan->id)->whereUserId($user->id)->delete();

        if ($request->is_dislike == "true") {
          ObrolanDislike::firstOrCreate(['obrolan_id'=>$obrolan->id, 'user_id'=>$user->id]);
          ObrolanPoin::firstOrCreate(['obrolan_id'=>$obrolan->id, 'user_id'=>$user->id,'point'=>3,'model_name'=>'ObrolanDislike']);
          User::find($obrolan->user_id)->increment('count_dislike');
          $obrolan->increment('poin',3);
        }

        if ($request->is_dislike == "false") {
          ObrolanDislike::firstOrCreate(['obrolan_id'=>$obrolan->id, 'user_id'=>$user->id])->delete();
          ObrolanPoin::firstOrCreate(['obrolan_id'=>$obrolan->id, 'user_id'=>$user->id,'point'=>3,'model_name'=>'ObrolanDislike'])->delete();
          try {
            User::find($obrolan->user_id)->decrement('count_dislike');
            $obrolan->decrement('poin',3);
          } catch (\Exception $e) {

          }

        }

         $count_dislike  = ObrolanDislike::whereObrolanId($obrolan->id)->count();
         $count_like     = ObrolanLike::whereObrolanId($obrolan->id)->count();
         $count_view     = ObrolanView::whereObrolanId($obrolan->id)->count();
         $count_comment  = ObrolanKomentar::whereNull('parent_id')->whereObrolanId($obrolan->id)->count();

         $obrolan->append('is_like');
         $obrolan->append('is_dislike');
         $obrolan->append('media');

         $obrolan->update([
           "count_dislike" => $count_dislike,
           "count_like"    => $count_like,
           "view"          => $count_view,
           "count_comment" => $count_comment,
         ]);
         $data = array(
           "obrolan" =>$obrolan
         );

       $this->res->data = $data;
       return \response()->json($this->res);
     }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\ObrolanDislike  $obrolanDislike
     * @return \Illuminate\Http\Response
     */
    public function show(ObrolanDislike $obrolanDislike)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\ObrolanDislike  $obrolanDislike
     * @return \Illuminate\Http\Response
     */
    public function edit(ObrolanDislike $obrolanDislike)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\ObrolanDislike  $obrolanDislike
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ObrolanDislike $obrolanDislike)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\ObrolanDislike  $obrolanDislike
     * @return \Illuminate\Http\Response
     */
    public function destroy(ObrolanDislike $obrolanDislike)
    {
        //
    }
}
