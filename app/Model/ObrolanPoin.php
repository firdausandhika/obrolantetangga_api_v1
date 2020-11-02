<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObrolanPoin extends Model
{
    use SoftDeletes;
    protected $guarded = [];
}
