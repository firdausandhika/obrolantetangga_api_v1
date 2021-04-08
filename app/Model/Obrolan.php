<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Carbon\Carbon;
use Illuminate\Support\Str;
use App\Model\Wilayah;
use App\Model\ObrolanView;

use App\Model\ObrolanLike;
use App\Model\ObrolanKomentar;
use App\Model\ObrolanDisike;

class Obrolan extends Model
{
    use SoftDeletes;
    protected $table = 'obrolans';
    protected $guarded = [];
    protected $appends = ['last_time','is_luar_kota'];
    protected $hidden = ['id','deleted_at','updated_at','kategori_id','user_id'];

    public function scopeFilter($query, $request)
    {
      if (($request->has('cari')) && ($request->cari != '')) {
        return $query->where('kontent', 'like', '%'.$request->cari.'%');
      }
    }

    public function kategori() {
        return $this->belongsTo('App\Model\Kategori', 'kategori_id');
    }

    public function user() {
        return $this->belongsTo('App\Model\User', 'user_id');
    }

    public function obrolan_gambar() {
        return $this->hasMany('App\Model\ObrolanGambar');
    }

    public function obrolan_komentar() {
        return $this->hasMany('App\Model\ObrolanKomentar');
    }

    public function obrolan_like() {
        return $this->hasMany('App\Model\ObrolanLike');
    }
    public function obrolan_dislike() {
        return $this->hasMany('App\Model\ObrolanDislike');
    }

    public function _kelurahan() {
        return $this->belongsTo('App\Model\Wilayah', 'wilayah', 'kode');
    }

    public function getProvinsiKodeAttribute() {
      return Str::substr($this->wilayah, 0, 2);
    }

    public function getKotaKodeAttribute() {
      return Str::substr($this->wilayah, 0, 5);
    }

    public function getKecamatanKodeAttribute() {
      return Str::substr($this->wilayah, 0, 8);
    }

    //
    //

    public function getProvinsiDataAttribute() {
      return Wilayah::whereKode(Str::substr($this->wilayah, 0, 2))->first();
    }

    public function getKotaDataAttribute() {
      return Wilayah::whereKode(Str::substr($this->wilayah, 0, 5))->first();
    }

    public function getKecamatanDataAttribute() {
      return Wilayah::whereKode(Str::substr($this->wilayah, 0, 8))->first();
    }

    public function getKelurahanDataAttribute() {
      return Wilayah::whereKode($this->wilayah)->first();
    }

    public function getIsLuarKotaAttribute() {
      return $this->wilayah != $this->current_wilayah_user;
    }



    public function getCurrentUserAttribute() {
      return auth()->user();
    }

    public function getNewKomentarAttribute() {
      return ObrolanKomentar::whereObrolanId($this->id)->orderBy('id','desc')
      ->with('user')
      ->with(['sub_komen' => function ($sub_query) {
                // $sub_query->limit(1);
                $sub_query->orderBy('id','asc');
                $sub_query->with('user');
                $sub_query->limit(1);
            }])
      ->limit(3)->get();
    }

    public function obrolan_video() {
      return $this->hasMany('App\Model\ObrolanVideo');
  }

    public function getMediaAttribute() {
     $array_merge = array_merge(\json_decode($this->obrolan_gambar), \json_decode($this->obrolan_video));
     usort($array_merge,function($a1, $a2) {
          return $a1->urutan - $a2->urutan; // $v2 - $v1 to reverse direction
       });

       return $array_merge;
   }

    // public function getIsLikeAttribute() {
    //   return $this->count_like;
    // }
    //
    // public function getIsDislikeAttribute() {
    //   return $this->count_dislike;
    // }

    // public function getTotalViewAttribute() {
    //
    //   if ($this->view == null) {
    //     return 0;
    //   }
    //   return $this->view;
    // }

    public function getTotalViewAttribute() {
      return ObrolanView::whereObrolanId($this->id)->count();
    }

    public function getIsLikeAttribute() {
      return ObrolanLike::whereObrolanId($this->id)->whereUserId($this->current_user->id)->count();
    }

    public function getIsDislikeAttribute() {
      return ObrolanDislike::whereObrolanId($this->id)->whereUserId($this->current_user->id)->count();
    }





    public function getLastTimeAttribute()
    {
      $t1 = Carbon::now();
      $t2 = Carbon::parse($this->created_at);

      $diff = $t1->diff($t2);

      if ($diff->y > 0) {
        return  "{$diff->y} Tahun yang lalu";
      }

      if ($diff->m > 0) {
        return  "{$diff->m} Bulan yang lalu";
      }

      if ($diff->d > 0) {
        return  "{$diff->d} Hari yang lalu";
      }

      if ($diff->h > 0) {
        return  "{$diff->h} Jam yang lalu";
      }

      if ($diff->i > 0) {
        return  "{$diff->i} Menit yang lalu";
      }

      if ($diff->s > 0) {
        return  "{$diff->s} Detik yang lalu";
      }

      return "Beberapa Detik yang lalu";
    }

}
