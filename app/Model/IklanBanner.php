<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IklanBanner extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function letaks()
    {
        return $this->hasMany('App\Model\IklanBannerLetak');
    }

    public function myprovinsi()
    {
        return $this->hasOne('App\Model\Wilayah', 'kode', 'provinsi');
    }

    public function mykota()
    {
        return $this->hasOne('App\Model\Wilayah', 'kode', 'wilayah');
    }

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $request)
    {
        if ($request->get('search')) {
            return $query->whereHas('mykota', function($q) use ($request) {
                $q->where('nama', 'LIKE', '%'.$request->search.'%');
            })->orWhereHas('myprovinsi', function($q) use ($request) {
                $q->where('nama', 'LIKE', '%'.$request->search.'%');
            });  
        }
        return $query;
    }
}
