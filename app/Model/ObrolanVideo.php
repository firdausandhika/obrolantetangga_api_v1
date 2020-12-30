<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObrolanVideo extends Model
{
    use SoftDeletes;
    protected $table = 'obrolans_videos';
    protected $guarded = [];
    protected $appends = ['image_url','type'];

    public function getImageUrlAttribute(){
      return env('APP_URL')."/storage/obrolan"."/".$this->video;
    }

    public function getTypelAttribute(){
      return 'video';
    }
}
