<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObrolanView extends Model
{
    use SoftDeletes;
    protected $guarded = [];
}
