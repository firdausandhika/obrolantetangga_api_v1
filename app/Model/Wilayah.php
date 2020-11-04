<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wilayah extends Model
{
  // use SoftDeletes;

//   protected $fillable = [];
  protected $guarded = [];
  protected $hidden = ['id','deleted_at','updated_at','kategori_id','user_id','created_at'];

  public function scopeFilter($query, $request)
  {
    if (($request->has('search')) && ($request->search != '')) {
      return $query->where('name', 'like', '%'.$request->search.'%')
                  ->OrWhere('code', 'like', '%'.$request->search.'%');
    }
  }


}
