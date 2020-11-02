<?php
namespace App\Http\Controllers\V1;

use JWTAuth;
use Carbon\Carbon;
use App\Model\User;
use App\Model\Wilayah;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

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
