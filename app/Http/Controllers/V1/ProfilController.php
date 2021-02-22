<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\V1\V1Controller;;
use Illuminate\Http\Request;
use App\Model\Obrolan;
use App\Model\User;
use Illuminate\Support\Str;
use App\Http\Controllers\V1\ObrolanController;


class ProfilController extends V1Controller
{

    public function __construct(Request $request,ObrolanController $obrolanController)
    {
        $this->middleware('jwt.verify');
        $this->user = $this->user();
        $this->res  = $this->res();
        $this->res->request = $request->except('_token');
        $this->res->ip = $request->ip();
        $this->res->user_agen = $request->header('User-Agent');
        $this->obv = $obrolanController;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

      public function index(Request $request)
      {
        $next_token = $request->next_token;
        if ($next_token == null) {
          $next_token =  $this->user->unik_user."_".Str::random(10);
        }

        $query = Obrolan::query()->filter($request)
        ->whereNotIn('id', function($q)
        {
           $q->from('reports')->whereNull('deleted_at')
            ->select('obrolan_id')
            ->where('user_id', auth()->user()->id)->whereNull('kategori_postingan');
        })
        ->whereNotIn('id', function($q) use($next_token)
        {
           $q->from('model_tmp_obrolans')->whereNull('deleted_at')
            ->select('obrolan_id')
            ->where('user_id', $this->user->id)->whereNextToken($next_token);
        })
        ->with('kategori')
        ->with('obrolan_gambar')
        ->with('user')
        ->orderBy('created_at', 'DESC')
        ->where("user_id",$this->user->id)
        ->limit(5);

        $obrolans =  $query->get();

        $this->obv->view($obrolans,$this->user,$next_token);
        $obrolans->each(function ($items) {
            $items->append('is_like');
            $items->append('is_dislike');
            $items->append('media');
           });
        $this->res->data =  ['obrolans'=>$obrolans,'next_token'=>$next_token];

        return \response()->json($this->res);
      }

      public function profil_user(Request $request,$unik_user)
      {
        $user = User::whereUnik($unik_user)->first();
        $this->res->data =  ['user'=>$user];
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
