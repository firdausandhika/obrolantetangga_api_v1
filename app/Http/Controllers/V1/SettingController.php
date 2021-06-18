<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\V1\V1Controller;;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Obrolan;
use Hash;
use \Carbon\Carbon;
use JWTAuth;


class SettingController extends V1Controller
{

    public function __construct(Request $request)
    {
      $this->middleware('jwt.verify');
      $this->user = $this->user();
      $this->res  = $this->res();
      $this->res->request = $request->except('_token');
      $this->res->ip = $request->ip();
      $this->res->user_agen = $request->header('User-Agent');
    }

    public function change_password(Request $request)
    {

      if ($request->password_lama == null) {
        $this->res->success = false;
        $this->res->msg = "Password Lama Tidak Boleh Kosong";
        return \response()->json($this->res);
      }

      if ($request->password_baru == null) {
        $this->res->success = false;
        $this->res->msg = "Password Lama Tidak Boleh Kosong";
        return \response()->json($this->res);
      }


      $user   = User::find($this->user->id);
      if (!Hash::check($request->password_lama, $user->password)) {
        $this->res->success = false;
        $this->res->msg = "Password Lama Anda Salah";
        return \response()->json($this->res);
      }

      try {
        $user->update([
          'password'=>\bcrypt($request->password_baru)
        ]);
      } catch (\Exception $e) {
        $this->res->success = false;
        $this->res->msg = $e;
        return \response()->json($this->res);
      }

      $this->res->msg   = "Success";
      auth()->logoutOtherDevices($request->password_baru);
      auth()->logout();
      return \response()->json($this->res);
    }

    public function change_location(Request $request)
    {

      if ($request->provinsi == null) {
        $this->res->success = false;
        $this->res->msg = "provinsi tidak boleh kosong";
        return \response()->json($this->res);
      }

      if ($request->kota == null) {
        $this->res->success = false;
        $this->res->msg = "kota tidak boleh kosong";
        return \response()->json($this->res);
      }

      if ($request->kecamatan == null) {
        $this->res->success = false;
        $this->res->msg = "kecamatan tidak boleh kosong";
        return \response()->json($this->res);
      }

      if ($request->kelurahan == null) {
        $this->res->success = false;
        $this->res->msg = "kelurahan tidak boleh kosong";
        return \response()->json($this->res);
      }


      $t1 = Carbon::now();
      $t2 = Carbon::parse($this->user->updated_at);
      $diff_d = $t1->diffInDays($t2);
      // return $request->kota;
      if ($diff_d < 3) {
        $this->res->success = false;
        $this->res->msg = "Mengganti Kota Hanya Bisa 3 Hari Sekali";
        return \response()->json($this->res);
      }

      $user = User::find($this->user->id);

      try {
        $user->update([
          "provinsi"=> $request->provinsi,
          "kota"=> $request->kota,
          "kecamatan"=> $request->kecamatan,
          "kelurahan"=> $request->kelurahan,
        ]);
      } catch (JWTException $e) {
        $this->res->success = false;
        $this->res->msg = $e;
        return \response()->json($this->res);
      }

      $this->res->msg   = "Success";
      $this->res->data   = ["user"=>User::find($this->user->id)];
      JWTAuth::invalidate(JWTAuth::getToken());
      return \response()->json($this->res);
    }

    public function change_info(Request $request)
    {
      if ($request->nama == null) {
        $this->res->success = false;
        $this->res->msg = "nama tidak boleh kosong";
        return \response()->json($this->res);
      }

      if ($request->email == null) {
        $this->res->success = false;
        $this->res->msg = "email tidak boleh kosong";
        return \response()->json($this->res);
      }

      if ($request->phone == null) {
        $this->res->success = false;
        $this->res->msg = "phone tidak boleh kosong";
        return \response()->json($this->res);
      }

      if ($request->tanggal_lahir == null) {
        $this->res->success = false;
        $this->res->msg = "tanggal lahir tidak boleh kosong";
        return \response()->json($this->res);
      }

      if ($request->seks == null && $request->sex == null) {
        $this->res->success = false;
        $this->res->msg = "jenis kelamin tidak boleh kosong";
        return \response()->json($this->res);
      }

      if ($request->biografi == null) {
        $this->res->success = false;
        $this->res->msg = "biografi tidak boleh kosong";
        return \response()->json($this->res);
      }

      $input = $request->all();
      if ($input['phone'][0] == '0') {
            $input['phone'] = substr($input['phone'], 1);
      }

      if (!isset($input['seks'])) {
        $input['seks'] = $input['sex'];
        unset($input['sex']);

      }

      unset($input['sex']);

      $user = User::find($this->user->id);

      try {
        User::find($this->user->id)->update($input);
      } catch (\JWTException $e) {
        $this->res->success = false;
        $this->res->msg = $e;
        return \response()->json($this->res);
      }

      $this->res->msg   = "Success";
      $this->res->data   = ["user"=>User::find($this->user->id)];
      return \response()->json($this->res);


    }

    public function change_token_firebase(Request $request)
    {

      if ($request->token_firebase == null) {
        $this->res->success = false;
        $this->res->msg = "Token Firebase Tidak Boleh Kosong";
        return \response()->json($this->res);
      }

      $user   = User::find($this->user->id);

      try {
        $user->update([
          'token_firebase'=>$request->token_firebase
        ]);
      } catch (\Exception $e) {
        $this->res->success = false;
        $this->res->msg = $e;
        return \response()->json($this->res);
      }

      $this->res->msg   = "Success";
      return \response()->json($this->res);
    }
}
