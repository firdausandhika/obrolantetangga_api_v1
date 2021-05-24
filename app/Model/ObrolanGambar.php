<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObrolanGambar extends Model
{
    use SoftDeletes;
    protected $table = 'obrolans_gambars';
    protected $guarded = [];
    protected $appends = ['image_url','type'];

    public function getImageUrlAttribute(){
      return $this->gambar;
    }

    public function getTypeAttribute(){
      return 'image';
    }
}
