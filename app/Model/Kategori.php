<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kategori extends Model
{
    use SoftDeletes;
    protected $table = 'kategoris';
    protected $guarded = [];
    protected $appends = ['image_url'];
    protected $hidden = ['deleted_at','updated_at','kategori_id','user_id','jenis_id'];

    public function getImageUrlAttribute(){
      return "https://obrolantetangga.com/frontend/asset/img/kategori"."/".$this->icon;
    }
}
