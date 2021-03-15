<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IklanDefault extends Model
{
    use SoftDeletes;
    protected $guarded = [];
}
