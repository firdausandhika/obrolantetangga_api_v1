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
// use Illuminate\Database\Eloquent\Factory;


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
      } catch (JWTJWTException $e) {
          return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>'could_not_create_token'], 500);
      }

      $user = User::wherePhone($credentials['phone'])->first();

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
      } catch (\JWTException $e) {
        return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>$e], 500);
      }


      try {
        $this->send_otp_phone($user);
      } catch (\JWTException $e) {
        return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>$e], 500);
      }

      $token = JWTAuth::fromUser($user);
      return response()->json(['success'=>true, 'request'=>$request->except('_token'), 'msg' =>'Registerasi Berhasil','user'=>$user,'token'=>$token],201);
  }

  public function forgot(Request $request)
    {
      // return phpinfo();


      if ($request->phone[0] == '0') {
        $request->merge(['phone' => substr($request->phone, 1)]);
      }
      // return $request->phone;

      $user = User::wherePhone($request->phone)->first();

      try {
        $user->nama;
      } catch (\Exception $e) {
        return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>'User Tidak Ada'], 500);
      }

      $password_reset = PasswordReset::create([
        'unik_user'=>$user->unik_user,
        'token'=> str_replace('-', '', $this->makeUuid())
      ]);


      $this->send_sms_link_reset($password_reset,$user);


      return response()->json(['success'=>true, 'request'=>$request->except('_token'),'msg' =>'SMS Telah Dikirim','user'=>$user],201);
    }

    public function send_sms_link_reset($password_reset,$users)
    {
        // print_r([env('KEY_WA_DEMO'),env('URL_WA'),env('AUTH_WA_KEY')]);exit;
        $number = $users->nomor;
        $rtrim = $number - rtrim('0');
        // $users = User::wherePhone($rtrim)->first();
        // dd($users);

        // $user =  User::wherePhone($users)->first();
        $password_reset = PasswordReset::create([
            'unik_user' => $users->unik_user,
            'token' => str_replace('-', '', $this->makeUuid())
        ]);
        // dd($user);
        $message = "Ini adalah Link rahasia untuk mereset password akun ObrolanTetangga anda https://obrolantetanngga.com/" . "reset_password" . "/" . $password_reset->token . ". Silahkan klik atau buka Link tersebut pada browser anda. Jangan sebarkan kepada siapapun bahkan kepada pihak ObrolanTetangga sekalipun. Hati-hati penipuan!";
        // dd("+62".$users->phone);
        $key_demo =env('KEY_WA_DEMO');
        $url = env('URL_WA');
        $data = array(
            "phone_no" => "+62" . $users->phone,
            "key"     => $key_demo,
            "message" => $message
        );

        $data_string = json_encode($data, 1);

        try {
          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_VERBOSE, 0);
          curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
          curl_setopt($ch, CURLOPT_TIMEOUT, 10);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
              'Content-Type: application/json',
              'Content-Length: ' . strlen($data_string),
              'Authorization: Basic '.env('AUTH_WA_KEY')
          ));
          $result_curl  = curl_exec($ch);
          curl_close($ch);
        } catch (\Exception $e) {
          print_r($e);exit;
        }
        //
        //
        // print_r($result_curl);exit;
        print_r("sampai sini fin 123");exit;


      // // KIRIM SMS
      //   // setting
      //   $apikey      = 'ba303b7838da487b5b00d36c03941f57'; // api key
      //   $urlendpoint = 'http://sms114.xyz/sms/api_sms_otp_send_json.php'; // url endpoint api
      //   $callbackurl = ''; // url callback get status sms
      //
      //   // create header json
      //   $senddata = array(
      //       'apikey' => $apikey,
      //       'callbackurl' => $callbackurl,
      //       'datapacket' => array()
      //   );
      //
      //   if ($user->phone[0] != '0') {
      //     $user->phone = '0'.$user->phone;
      //   }
      //
      //   // create detail data json
      //   $wew = \URL::to('/')."/"."reset_password"."/".$password_reset->token;
      //   $message = "[obrolantetangga] Klik tautan ini untuk mengganti password ".$wew;
      //   array_push($senddata['datapacket'], array(
      //       'number' => $user->phone,
      //       'message' => $message
      //   ));
      //   // sending
      //   $request = json_encode($senddata);
      //   $curlHandle = curl_init($urlendpoint);
      //   curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, "POST");
      //   curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $request);
      //   curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
      //   curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array(
      //       'Content-Type: application/json',
      //       'Content-Length: ' . strlen($request)
      //   ));
      //   curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
      //   curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, 30);
      //   $respon = curl_exec($curlHandle);
      //   curl_close($curlHandle);

    }

  public function send_otp_phone($user)
  {
    // // KIRIM SMS
    //     // setting
    //     $apikey      = 'ba303b7838da487b5b00d36c03941f57'; // api key
    //     $urlendpoint = 'http://sms114.xyz/sms/api_sms_otp_send_json.php'; // url endpoint api
    //     $callbackurl = ''; // url callback get status sms
    //
    //     // create header json
    //     $senddata = array(
    //         'apikey' => $apikey,
    //         'callbackurl' => $callbackurl,
    //         'datapacket' => array()
    //     );
    //
    //     if ($user->phone[0] != '0') {
    //       $user->phone = '0'.$user->phone;
    //     }
    //
    //     // create detail data json
    //     $message = "[obrolantetangga] Kode OTP : {$user->otp}. Hati-hati penipuan!";
    //     array_push($senddata['datapacket'], array(
    //         'number' => $user->phone,
    //         'message' => $message
    //     ));
    //     // sending
    //     $request = json_encode($senddata);
    //     $curlHandle = curl_init($urlendpoint);
    //     curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, "POST");
    //     curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $request);
    //     curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array(
    //         'Content-Type: application/json',
    //         'Content-Length: ' . strlen($request)
    //     ));
    //     curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
    //     curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, 30);
    //     $respon = curl_exec($curlHandle);
    //     curl_close($curlHandle);

    // $user = auth()->user();
       $message = "[obrolantetangga] Ini adalah kode OTP rahasia untuk masuk ke akun ObrolanTetangga anda : {$user->otp}, Jangan sebarkan ke siapapun bahkan ke pihak ObrolanTetangga sekalipun. Hati-hati penipuan!";
       // dd("+62". $user->phone);
       $key_demo =env('KEY_WA_DEMO');
       $url = env('URL_WA');
       $data = array(
           "phone_no" => "+62" . $user->phone,
           "key"     => $key_demo,
           "message" => $message
       );
       $data_string = json_encode($data,1);

       $ch = curl_init($url);
       curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
       curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_VERBOSE, 0);
       curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
       curl_setopt($ch, CURLOPT_TIMEOUT, 360);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
       curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       'Content-Type: application/json',
       'Content-Length: ' . strlen($data_string),
       'Authorization: Basic '.env('AUTH_WA_KEY')
       ));
       curl_exec($ch);
       curl_close($ch);

  }

    public function otp(Request $request)
    {
      try {
        $user_jwt = JWTAuth::parseToken()->authenticate();
      } catch (JWTException $e) {
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
          'current_wilayah_user'=>$user->kelurahan,
        ]
      );
    }

    public function resend_otp(Request $request)
    {
      try {
        $user_jwt = JWTAuth::parseToken()->authenticate();
      } catch (JWTException $e) {
        return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>'Akun Tidak Ditemukan'], 404);
      }

      try {
        $user             = User::whereId($user_jwt->id)->first();
        $user->otp        = \substr(str_shuffle("0123456789"), 0, 4);
        $user->save();
      } catch (\JWTException $e) {
        return array('success'=>$e);
      }

      try {
          $this->send_otp_phone($user);
      } catch (\JWTException $e) {
        return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>$e], 500);
      }

      return response()->json(['success'=>true, 'request'=>$request->except('_token'), 'msg' =>'OTP berhasil dikirim','user'=>$user],201);
    }

    public function create_new_phone_number(Request $request)
    {

      $user = [
        'phone'=>str_replace('(+62)','',str_replace(' ','',$faker->phoneNumber)),
        'name'=>'testing '.$faker->name,

      ];
      return response()->json(['success'=>true, 'data'=>$user,'request'=>$request->except('_token'), 'msg' =>'success'],200);
    }

    public function get_otp(Request $request)
    {
      try {
        $user_jwt = JWTAuth::parseToken()->authenticate();
      } catch (JWTException $e) {
        return response()->json(['success'=>false, 'request'=>$request->except('_token'), 'msg' =>'Akun Tidak Ditemukan'], 404);
      }

      try {
        $user = User::whereId($user_jwt->id)->first();
        $otp  = $user->otp;
      } catch (\JWTException $e) {
        return array('success'=>$e);
      }

      return response()->json(['success'=>true, 'data'=>['otp'=>$otp],'request'=>$request->except('_token'), 'msg' =>'success'],200);
    }

    public function makeUuid()
    {
      return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
    }
}
