<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObrolanDislike extends Model
{
    use SoftDeletes;
    protected $table = 'obrolans_dislikes';
    protected $guarded = [];
}
