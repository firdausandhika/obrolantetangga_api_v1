<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;

// ObrolanView

use Illuminate\Http\Request;
use DB;
use App\Model\User;
use App\Model\Obrolan;
use App\Model\Kategori;
use App\Model\ObrolanLike;
use App\Model\ObrolanPoin;
use App\Model\ObrolanView;
use App\Model\ObrolanDislike;
use App\Model\ObrolanKomentar;
use App\Http\Controllers\V1\V1Controller;
// use App\Http\Controllers\ObrolanViewController;

use Illuminate\Support\Str;

class TrendingController extends V1Controller
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

     $user = auth()->user();
     // return Str::substr($user->kota,0,5);
      // $kategoris = Kategori::where('jenis_id', 1)->orderBy('nama', 'ASC')->get();
     // $tetanggas = User::where('id','!=',$user->id)->whereKota($user->kota)->limit(8)->orderBy('id','desc')->get();

     // $obrolans = Obrolan::filter($request)
     //          ->orderBy('poin','desc')
     //          ->whereRaw('LEFT(wilayah,5)=LEFT(current_wilayah_user,5)')
     //          ->whereRaw('LEFT(wilayah,5)="'.$user->kota.'"')
     //          // ->with('kategori')
     //          // ->with('obrolan_gambar')
     //          // ->with(['obrolan_komentar' => function ($query) {
     //          //     $query->whereNull('parent_id');
     //          //     $query->orderBy('id','asc');
     //          //     $query->with('user');
     //          //     $query->with(['sub_komen' => function ($sub_query) {
     //          //         // $sub_query->limit(1);
     //          //         $sub_query->orderBy('id','asc');
     //          //         $sub_query->with('user');
     //          //     }]);
     //          // }])
     //          // ->with('obrolan_like')
     //          // ->with('obrolan_dislike')
     //          ->with('user')
     //          ->limit(1)
     //          ->get();

     $obrolans = Obrolan::filter($request)
             ->orderBy('poin','desc')
             ->whereRaw('LEFT(wilayah,5)=LEFT(current_wilayah_user,5)')
             ->whereRaw('LEFT(wilayah,5)="'.$user->kota.'"')
             ->where('poin', '>', 0)
             // ->where("obrolans.wilayah", "like", Str::substr($user->kota,0,5))
             // ->orWhere("obrolans.wilayah", "")
             ->with('kategori')
             ->with('obrolan_gambar')
             ->with(['obrolan_komentar' => function ($query) {
                 $query->whereNull('parent_id');
                 $query->orderBy('id','asc');
                 $query->with('user');
                 $query->with(['sub_komen' => function ($sub_query) {
                     // $sub_query->limit(1);
                     $sub_query->orderBy('id','asc');
                     $sub_query->with('user');
                 }]);
             }])
             ->with('obrolan_like')
             ->with('obrolan_dislike')
             ->with('user')
             ->paginate(3);

     Obrolan::whereRaw('LEFT(wilayah,5)=LEFT(current_wilayah_user,5)')
               ->whereRaw('LEFT(wilayah,5)="'.$user->kota.'"')
               ->whereNotIn('id',$obrolans->pluck('id')->toArray())
               ->update(['rank'=>null]);

       $this->view($obrolans,$user);
       $obrolans->each(function ($items) {
       $items->append('provinsi_data');
       $items->append('kota_data');
       $items->append('kecamatan_data');
       $items->append('kelurahan_data');
       $items->append('total_view');
       $items->append('current_user');
       $items->append('is_like');
       $items->append('is_dislike');
       $items->append('media');
       });

       // return $obrolans;
      $this->res->data = ['obrolans'=>$obrolans];
      return \response()->json($this->res);
     // $data = compact('user', 'obrolans', 'kategoris','tetanggas');
     // return view('frontend.trending', $data);
     // $data       = compact('kategoris');
     // return view('frontend.trending',$data);
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


    public function update_point(Request $request)
    {
      $obrolans = Obrolan::filter($request)->leftJoin('obrolan_poins', function($join) {
                      $join->on('obrolan_poins.obrolan_id', '=', 'obrolans.id');
                  })
               ->select('obrolans.*', DB::raw('sum(obrolan_poins.point) as total_point'))
               ->groupBy('obrolans.id')
               ->orderBy('total_point','desc')
               ->get();

      foreach($obrolans as $obrolan){
        Obrolan::whereId($obrolan->id)->update(['poin'=>$obrolan->total_point]);
      }

      return $obrolans;
    }

    public function view($obrolans,$user)
    {
      foreach($obrolans as $obrolan){
        if ($obrolan->user_id != $user->id) {
          ObrolanView::firstOrCreate([
            'obrolan_id'=>$obrolan->id,
            'user_id'=>$user->id
          ]);
        }
      }
    }
}
