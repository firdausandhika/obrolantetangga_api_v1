<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObrolanGambar extends Model
{
    use SoftDeletes;
    protected $table = 'obrolans_gambars';
    protected $guarded = [];
    protected $appends = ['image_url'];

    public function getImageUrlAttribute(){
      return env('APP_URL')."/storage/obrolan"."/".$this->gambar;
    }
}
