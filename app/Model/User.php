<?php

namespace App\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Model\Wilayah;
use App\Model\Users;
use \Carbon\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $appends = ['is_new'];
    // protected $hidden = [''];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','otp',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function _kota() {
        return $this->belongsTo('App\Model\Wilayah', 'kota', 'kode');
    }

    public function _provinsi() {
        return $this->belongsTo('App\Model\Wilayah', 'provinsi', 'kode');
    }

    public function _kecamatan() {
        return $this->belongsTo('App\Model\Wilayah', 'kecamatan', 'kode');
    }

    public function _kelurahan() {
        return $this->belongsTo('App\Model\Wilayah', 'kelurahan', 'kode');
    }

    public function obrolans() {
        return $this->hasMany('App\Model\Obrolan');
    }

    public function obrolan_likes() {
        return $this->hasMany('App\Model\ObrolanLike');
    }

    public function obrolan_dislikes() {
        return $this->hasMany('App\Model\ObrolanDislike');
    }

    public function obrolan_reports() {
        return $this->hasMany('App\Model\Report');
    }

    // public function obrolans() {
    //     return $this->belongsTo('App\Model\Obrolan', 'kelurahan', 'kode');
    // }

    public function getAllKelurahanAttribute()
    {
        return Wilayah::whereRaw("LEFT(kode,8)='{$this->kecamatan}'")
        ->whereRaw("CHAR_LENGTH(kode)=13")
        ->orderBy('nama','asc')->get();
    }

    public function getAllKecamatanAttribute()
    {
        return Wilayah::whereRaw("LEFT(kode,5)='{$this->kota}'")
        ->whereRaw("CHAR_LENGTH(kode)=8")
        ->orderBy('nama','asc')->get();
    }

    public function getAllKotaAttribute()
    {
        return Wilayah::whereRaw("LEFT(kode,2)='{$this->provinsi}'")
        ->whereRaw("CHAR_LENGTH(kode)=5")
        ->orderBy('nama','asc')->get();
    }

    public function getAllProvinsiAttribute()
    {
        return Wilayah::whereRaw('LENGTH(kode) < 3')->orderBy('nama','asc')->get();
    }

    public function getIsNewAttribute()
    {
      $t1 = Carbon::now();
      $t2 = Carbon::parse($this->created_at);

      $diff = $t1->diff($t2);

      if ($diff->d < 1) {
        return  true;
      }

      return false;
    }

    public function getJWTIdentifier() {
        return $this->getKey();
    }
    public function getJWTCustomClaims() {
        return [];
    }


}
