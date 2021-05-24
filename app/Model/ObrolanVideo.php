<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObrolanVideo extends Model
{
    use SoftDeletes;
    protected $table = 'obrolans_videos';
    protected $guarded = [];
    protected $appends = ['video_url','type'];

    public function getVideoUrlAttribute(){
      return $this->video;
    }

    public function getTypeAttribute(){
      return 'video';
    }
}
