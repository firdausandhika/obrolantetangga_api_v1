<?php

namespace App\Model;

use \Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notif extends Model
{
    use SoftDeletes;
    protected $table = 'notifs';
    protected $guarded = [];
    protected $appends = ['last_time'];

    public function user() {
        return $this->belongsTo('App\Model\User', 'user_from');
    }

    public function obrolan() {
        return $this->belongsTo('App\Model\Obrolan', 'obrolan_id');
    }

    public function getLastTimeAttribute()
    {
      $t1 = Carbon::now();
      $t2 = Carbon::parse($this->updated_at);

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
