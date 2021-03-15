<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class IklanBannerLetak extends Model
{
    protected $guarded = [];

    public function iklanbanner()
    {
        return $this->hasOne('App\Model\IklanBanner', 'id', 'iklan_banner_id');
    }
}
