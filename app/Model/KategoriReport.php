<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class KategoriReport extends Model
{
    use SoftDeletes;
    protected $guarded = [];
}
