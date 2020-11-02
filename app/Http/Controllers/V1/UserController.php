<?php
namespace App\Http\Controllers\V1;

use JWTAuth;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;


// use App\Providers\RouteServiceProvider;
// use App\Model\User;
use App\Model\Wilayah;
// use Illuminate\Foundation\Auth\RegistersUsers;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;
// use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
// use Auth;
// use Mail;

class UserController extends Controller
{
  public function login(Request $request)
  {
      $credentials = $request->only('phone', 'password');

      if ($input['phone'][0] == '0') {
            $input['phone'] = substr($input['phone'], 1);
      }
      // return $credentials;

      try {
          if (! $token = JWTAuth::attempt($credentials)) {
              return response()->json(['error' => 'invalid_credentials'], 500);
          }
      } catch (JWTException $e) {
          return response()->json(['error' => 'could_not_create_token'], 500);
      }

      return response()->json(compact('token'));
  }

  public function register(Request $request)
  {
      $input = $request->except('_token');

      if ($input['phone'][0] == '0') {
            $input['phone'] = substr($input['phone'], 1);
      }

      $n = User::where('phone',$request->phone)->count();

      if ($n > 0) {
          return response()->json(['error' => 'Nomor Handphone Telah Digunakan'], 500);
      }

      $dn             = Carbon::now();
      $dt             = Carbon::create($request->tanggal_lahir);
      $selisih_tahun  =  $dn->diffInYears($dt);

      if ($selisih_tahun < 10) {
        return response()->json(['error' => 'Anda Tidak Cukup Umur'], 500);
      }

      $input['password']  = bcrypt($request->password);
      $input['unik_user'] =  Str::random(10);
      $input['otp'] = \substr(str_shuffle("0123456789"), 0, 4);
        // return $input;
      try {
          $user = User::create($input);
      } catch (\Exception $e) {
        return response()->json(['error' => 'Gagal registerasi Harap Periksa Kembali Data Anda'], 500);
      }


      try {
        // $this->send_otp_phone($user);
        // $user->makeVisible('otp');
      } catch (\Exception $e) {
        return response()->json(['error' => $e], 500);
      }

      $token = JWTAuth::fromUser($user);

      return response()->json(compact('user','token'),201);



      // $validator = Validator::make($request->all(), [
      //     'name' => 'required|string|max:255',
      //     'email' => 'required|string|email|max:255|unique:users',
      //     'password' => 'required|string|min:6|confirmed',
      // ]);
      //
      // if($validator->fails()){
      //     return response()->json($validator->errors()->toJson(), 400);
      // }

      // $user = User::create($input);
      //
      // $token = JWTAuth::fromUser($user);
      //
      // return response()->json(compact('user','token'),201);
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

  public function getAuthenticatedUser()
  {
      try {

          if (! $user = JWTAuth::parseToken()->authenticate()) {
              return response()->json(['user_not_found'], 404);
          }

      } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

          return response()->json(['token_expired'], $e->getStatusCode());

      } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

          return response()->json(['token_invalid'], $e->getStatusCode());

      } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

          return response()->json(['token_absent'], $e->getStatusCode());

      }

      return response()->json(compact('user'));
  }
}
