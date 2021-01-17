<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IklanReports extends Model
{
    use SoftDeletes;
    protected $guarded = [];
}
