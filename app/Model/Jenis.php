<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jenis extends Model
{
    use SoftDeletes;
    protected $table = 'jenis';
    protected $guarded = [];
}
