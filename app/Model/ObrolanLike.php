<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObrolanLike extends Model
{
    use SoftDeletes;
    protected $table = 'obrolans_likes';
    protected $guarded = [];
}
