<?php

namespace App\Model;

use \Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObrolanKomentar extends Model
{
    use SoftDeletes;
    protected $table = 'obrolans_komentars';
    protected $guarded = [];
    protected $appends = ['last_time'];
    protected $hidden = ['id','deleted_at','updated_at','kategori_id','user_id','obrolan_id','parent_id'];

    public function user() {
        return $this->belongsTo('App\Model\User', 'user_id');
    }

    public function sub_komen() {
        return $this->hasMany('App\Model\ObrolanKomentar','parent_id','id');
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
