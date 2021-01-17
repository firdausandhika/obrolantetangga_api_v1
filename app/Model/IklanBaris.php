<?php

namespace App\Model;

// use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Carbon\Carbon;
use Illuminate\Support\Str;
use App\Model\Wilayah;


class IklanBaris extends Model
{
    use SoftDeletes;
    protected $table = 'iklan_baris';
    protected $guarded = [];
    protected $appends = ['last_time'];

    public function scopeFilter($query, $request)
    {
     
      return $query->where('kontent', 'like', '%'.$request->find.'%');
      
    }

    public function kategori() {
        return $this->belongsTo('App\Model\Kategori', 'kategori_id');
    }

    public function user() {
        return $this->belongsTo('App\Model\User', 'user_id');
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

      return "Beberapa detik yang lalu";
    }

}
