<?php
namespace App\Http\Controllers\V1;

use JWTAuth;
use Carbon\Carbon;
use App\Model\User;
use App\Model\Wilayah;
use App\Model\PasswordReset;
use App\Model\Obrolan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\V1\V1Controller;

class AuthController extends V1Controller
{
  public function login(Request $request)
  {
      $credentials = $request->only('phone', 'password');

      if ($credentials['phone'][0] == '0') {
            $credentials['phone'] = substr($credentials['phone'], 1);
      }

      // return $credentials;
      // return $credentials;

      try {
          if (! $token = JWTAuth::attempt($credentials)) {
              return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>'invalid_credentials'], 500);
          }
      } catch (JWTException $e) {
          return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>'could_not_create_token'], 500);
      }

      $user = User::wherePhone($request->phone)->first();

      // return response()->json(compact('token','user'));
      return response()->json(['success'=>true, 'msg' =>'Login Berhasil','user'=>$user,'token'=>$token],201);
  }

  public function register(Request $request)
  {
      $input = $request->except('_token');

      if ($request->phone == null) {
        return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>'phone Tidak Boleh Kosong'], 500);
      }


      if ($request->nama == null) {
        return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>'Nama Tidak Boleh Kosong'], 500);
      }

      if ($request->provinsi == null) {
        return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>'provinsi Tidak Boleh Kosong'], 500);
      }

      if ($request->kota == null) {
        return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>'kota Tidak Boleh Kosong'], 500);
      }

      if ($request->kecamatan == null) {
        return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>'kecamatan Tidak Boleh Kosong'], 500);
      }

      if ($request->kelurahan == null) {
        return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>'kelurahan Tidak Boleh Kosong'], 500);
      }

      if ($request->tanggal_lahir == null) {
        return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>'tanggal_lahir Tidak Boleh Kosong'], 500);
      }

      if ($input['phone'][0] == '0') {
        $input['phone'] = substr($input['phone'], 1);
      }

      $n = User::where('phone',$input['phone'])->count();

      if ($n > 0) {
          return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>'Nomor Handphone Telah Digunakan'], 500);
      }

      $dn             = Carbon::now();
      $dt             = Carbon::create($request->tanggal_lahir);
      $selisih_tahun  =  $dn->diffInYears($dt);

      if ($selisih_tahun < 10) {
        return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>'Anda Tidak Cukup Umur'], 500);
      }

      $input['password']  = bcrypt($request->password);
      $input['unik_user'] =  Str::random(10);
      $input['otp']       = \substr(str_shuffle("0123456789"), 0, 4);
      try {
          $user = User::create($input);
      } catch (\Exception $e) {
        return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>$e], 500);
      }


      try {
        $this->send_otp_phone($user);
      } catch (\Exception $e) {
        return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>'Gagal registerasi Harap Periksa Kembali Data Anda'], 500);
      }

      $token = JWTAuth::fromUser($user);
      return response()->json(['success'=>true, 'request'=>$request->except('_token'), 'msg' =>'Registerasi Berhasil','user'=>$user,'token'=>$token],201);
  }

  public function forgot(Request $request)
    {
      $faker = \Faker\Factory::create('id_ID');

      if ($request->phone[0] == '0') {
        $request->merge(['phone' => substr($request->phone, 1)]);
      }

      $user = User::wherePhone($request->phone)->first();

      try {
        $user->nama;
      } catch (\Exception $e) {
        return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>'User Tidak Ada'], 500);
      }

      $password_reset = PasswordReset::create([
        'unik_user'=>$user->unik_user,
        'token'=> str_replace('-', '', $faker->uuid)
      ]);


      $this->send_sms_link_reset($password_reset,$user);


      return response()->json(['success'=>true, 'request'=>$request->except('_token'),'msg' =>'SMS Telah Dikirim','user'=>$user],201);
    }

    public function send_sms_link_reset($password_reset,$user)
    {
      // KIRIM SMS
        // setting
        $apikey      = 'ba303b7838da487b5b00d36c03941f57'; // api key
        $urlendpoint = 'http://sms114.xyz/sms/api_sms_otp_send_json.php'; // url endpoint api
        $callbackurl = ''; // url callback get status sms

        // create header json
        $senddata = array(
            'apikey' => $apikey,
            'callbackurl' => $callbackurl,
            'datapacket' => array()
        );

        if ($user->phone[0] != '0') {
          $user->phone = '0'.$user->phone;
        }

        // create detail data json
        $wew = \URL::to('/')."/"."reset_password"."/".$password_reset->token;
        $message = "[obrolantetangga] Klik tautan ini untuk mengganti password ".$wew;
        array_push($senddata['datapacket'], array(
            'number' => $user->phone,
            'message' => $message
        ));
        // sending
        $request = json_encode($senddata);
        $curlHandle = curl_init($urlendpoint);
        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($request)
        ));
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
        curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, 30);
        $respon = curl_exec($curlHandle);
        curl_close($curlHandle);

    }

  public function send_otp_phone($user)
  {
    // KIRIM SMS
        // setting
        $apikey      = 'ba303b7838da487b5b00d36c03941f57'; // api key
        $urlendpoint = 'http://sms114.xyz/sms/api_sms_otp_send_json.php'; // url endpoint api
        $callbackurl = ''; // url callback get status sms

        // create header json
        $senddata = array(
            'apikey' => $apikey,
            'callbackurl' => $callbackurl,
            'datapacket' => array()
        );

        if ($user->phone[0] != '0') {
          $user->phone = '0'.$user->phone;
        }

        // create detail data json
        $message = "[obrolantetangga] Kode OTP : {$user->otp}. Hati-hati penipuan!";
        array_push($senddata['datapacket'], array(
            'number' => $user->phone,
            'message' => $message
        ));
        // sending
        $request = json_encode($senddata);
        $curlHandle = curl_init($urlendpoint);
        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($request)
        ));
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
        curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, 30);
        $respon = curl_exec($curlHandle);
        curl_close($curlHandle);

  }

    public function otp(Request $request)
    {
      if (! $user_jwt = JWTAuth::parseToken()->authenticate()) {
          return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>'Akun Tidak Ditemukan'], 404);
      }

      if ($user_jwt->active > 0) {
        return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>'Akun Telah Aktif', 'user'=>$user_jwt], 404);
      }

      if ($request->otp != $user_jwt->otp) {
        return response()->json(['success'=>false, 'user'=>$user_jwt,'request'=>$request->except('_token'), 'msg' =>'Kode OTP Salah'], 404);
      }

      $user = User::with('_kota')->with('_kecamatan')->with('_kelurahan')->whereId($user_jwt->id)->first();
      $this->new_member_posting($user);
      $user->update(['active'=>1]);
      return response()->json(['success'=>true, 'msg' =>'Akun Anda telah aktif', 'user'=>$user_jwt,'request'=>$request->except('_token')]);


    }

    public function new_member_posting($user)
    {
      Obrolan::create(
        [
          'kontent'=> "Salam kenal warga (OT) ObrolanTetangga. Saya <b>{$user->nama}</b>, warga baru dari kelurahan <b>{$user->_kelurahan->nama}</b>",
          'kategori_id'=>10,
          'user_id'=>$user->id,
          'wilayah'=>$user->kelurahan,
          'unik'=> Str::random(20),
        ]
      );
    }
}
