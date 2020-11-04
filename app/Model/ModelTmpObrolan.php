<?php

namespace App\Model;

use \Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelTmpObrolan extends Model
{
  use SoftDeletes;
  protected $guarded = [];
  protected $hidden = ['id','deleted_at','updated_at','kategori_id','user_id','jenis_id'];
}
