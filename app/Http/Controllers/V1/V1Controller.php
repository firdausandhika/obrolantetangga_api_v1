<?php

namespace App\Http\Controllers\V1;

use JWTAuth;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class V1Controller extends Controller
{
    public function user()
    {
      try {
        return $user_jwt = JWTAuth::parseToken()->authenticate();
      } catch (\Throwable $th) {
        return null;
      }

    }

    public function url_storage()
    {
      return env('APP_URL');
    }

    public function res()
    {
      return (object) ['success'=>true,'data'=>[],'msg'=>'','user'=>$this->user()];
    }
}
