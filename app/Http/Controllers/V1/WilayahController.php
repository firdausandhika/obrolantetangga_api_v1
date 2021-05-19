<?php

namespace App\Http\Controllers\V1;

use App\Model\Wilayah;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\V1\V1Controller;
use App\Models\Gcp;
use App\Model\User;
use App\Model\Obrolan;
use App\Model\ObrolanGambar;
use Storage;

class WilayahController extends V1Controller
{
  public function __construct(Request $request)
  {
      $this->user = $this->user();
      $this->res  = $this->res();
      $this->res->request = $request->except('_token');
  }

  public function get_provinsi()
  {

    // $disk = Storage::disk('gcs');
    //
    //   $directory = "alfin_new";
    //   if (!file_exists($disk->path($directory))) {
    //     $disk->makeDirectory($directory);
    //   }
    //   $disk->put($directory.'/alfin1.txt', 'alfin');

    $this->res->data =  Wilayah::whereRaw("CHAR_LENGTH(kode)=2")
    ->orderBy("nama")->orderBy('nama','asc')->get();
    return \response()->json($this->res);
  }

  public function get_kota($id)
  {
    $this->res->data = Wilayah::whereRaw("LEFT(kode,2)='{$id}'")
    ->whereRaw("CHAR_LENGTH(kode)=5")
    ->orderBy("nama")->orderBy('nama','asc')->get();
    return \response()->json($this->res);
  }

  public function get_kecamatan($id)
  {
    $this->res->data = Wilayah::whereRaw("LEFT(kode,5)='{$id}'")
    ->whereRaw("CHAR_LENGTH(kode)=8")
    ->orderBy("nama")->orderBy('nama','asc')->get();
    return \response()->json($this->res);
  }

  public function get_kelurahan($id)
  {
    $this->res->data = Wilayah::whereRaw("LEFT(kode,8)='{$id}'")
    ->whereRaw("CHAR_LENGTH(kode)=13")
    ->orderBy("nama")->orderBy('nama','asc')->get();
    return \response()->json($this->res);
  }

  public function copyToGoogle()
  {
  $datas = [['id'=>'5','gambar'=>'https://storage.googleapis.com/obrolantetangga/5nbpEmr1ns/obrolan/gambar/znF09V_XYfwE.jpg'],
  ['id'=>'6','gambar'=>'https://storage.googleapis.com/obrolantetangga/sabqI3TvnS/obrolan/gambar/m30PZz4sB_fH.jpeg'],
  ['id'=>'7','gambar'=>'https://storage.googleapis.com/obrolantetangga/mDGREjwYp2/obrolan/gambar/Z74YmqawdWsH.jpg'],
  ['id'=>'8','gambar'=>'https://storage.googleapis.com/obrolantetangga/216yybW8Kf/obrolan/gambar/TlZDtvXkAuM0.jpg'],
  ['id'=>'9','gambar'=>'https://storage.googleapis.com/obrolantetangga/Zd7SZmWljN/obrolan/gambar/4b6DaAzCBtSN.jpg'],
  ['id'=>'10','gambar'=>'https://storage.googleapis.com/obrolantetangga/12tbDcgGdK/obrolan/gambar/I3R9iXleKdTH.jpg'],
  ['id'=>'19','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/C5JAzZEonlSP.jpg'],
  ['id'=>'26','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/fvqI8lrnab32.jpg'],
  ['id'=>'32','gambar'=>'https://storage.googleapis.com/obrolantetangga/5nbpEmr1ns/obrolan/gambar/57PtQyKx9h4B.jpg'],
  ['id'=>'33','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/GHnmtSvwuzdc.jpg'],
  ['id'=>'34','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/ZYS6s1rH4Ccu.jpg'],
  ['id'=>'35','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/h7Fuk0JYVl31.jpg'],
  ['id'=>'36','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/uGfMmL1E5n4C.jpg'],
  ['id'=>'37','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/Xt4JUI02Lhe1.jpg'],
  ['id'=>'38','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/BrEHJyOd3wR9.jpg'],
  ['id'=>'39','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/M1vTnYap43id.jpg'],
  ['id'=>'40','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/vEJURDYf9gq5.jpg'],
  ['id'=>'41','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/lAQ6nfJpex4c.jpg'],
  ['id'=>'42','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/W5viJ9s2DxId.jpg'],
  ['id'=>'43','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/mevCtKowqkJl.jpg'],
  ['id'=>'44','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/xKi_GFWvDHdE.jpg'],
  ['id'=>'45','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/SV_niWwuvKaN.jpg'],
  ['id'=>'46','gambar'=>'https://storage.googleapis.com/obrolantetangga/kJbM5VHRMR/obrolan/gambar/0lJPdQ8Gato4.jpg'],
  ['id'=>'47','gambar'=>'https://storage.googleapis.com/obrolantetangga/gKuVopHhBW/obrolan/gambar/RHonxYt_9Z3B.png'],
  ['id'=>'48','gambar'=>'https://storage.googleapis.com/obrolantetangga/gKuVopHhBW/obrolan/gambar/ERp9f6Ge_Thz.png'],
  ['id'=>'51','gambar'=>'https://storage.googleapis.com/obrolantetangga/5nbpEmr1ns/obrolan/gambar/Dxg6oMbSU1If.jpg'],
  ['id'=>'52','gambar'=>'https://storage.googleapis.com/obrolantetangga/zIaxNvSwMA/obrolan/gambar/dpkaDSvzNIMj.jpg'],
  ['id'=>'53','gambar'=>'https://storage.googleapis.com/obrolantetangga/kJbM5VHRMR/obrolan/gambar/MZkYyPAc_27D.jpg'],
  ['id'=>'57','gambar'=>'https://storage.googleapis.com/obrolantetangga/t3RYlo7n9C/obrolan/gambar/J6IP0E2oTQvu.jpg'],
  ['id'=>'60','gambar'=>'https://storage.googleapis.com/obrolantetangga/t3RYlo7n9C/obrolan/gambar/69QdOp2UHfVa.jpg'],
  ['id'=>'61','gambar'=>'https://storage.googleapis.com/obrolantetangga/1fiS4ZVEQb/obrolan/gambar/D6AC2zKxFQXO.jpg'],
  ['id'=>'62','gambar'=>'https://storage.googleapis.com/obrolantetangga/t3RYlo7n9C/obrolan/gambar/TVyx_roq7Npk.jpg'],
  ['id'=>'63','gambar'=>'https://storage.googleapis.com/obrolantetangga/kJbM5VHRMR/obrolan/gambar/znYoORbHhNm4.jpg'],
  ['id'=>'64','gambar'=>'https://storage.googleapis.com/obrolantetangga/zIaxNvSwMA/obrolan/gambar/hRvj4pg5y98B.jpg'],
  ['id'=>'68','gambar'=>'https://storage.googleapis.com/obrolantetangga/j5pl5LrdOp/obrolan/gambar/4xoChVzWau31.jpeg'],
  ['id'=>'69','gambar'=>'https://storage.googleapis.com/obrolantetangga/j5pl5LrdOp/obrolan/gambar/fE30Y6XjksVg.jpeg'],
  ['id'=>'75','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/tQxbgufVPeq6.jpg'],
  ['id'=>'77','gambar'=>'https://storage.googleapis.com/obrolantetangga/5nbpEmr1ns/obrolan/gambar/sx7TGEiPAt48.jpg'],
  ['id'=>'78','gambar'=>'https://storage.googleapis.com/obrolantetangga/5nbpEmr1ns/obrolan/gambar/1mFf8NyChzTU.png'],
  ['id'=>'79','gambar'=>'https://storage.googleapis.com/obrolantetangga/5nbpEmr1ns/obrolan/gambar/ekhKzlyM8P_3.jpg'],
  ['id'=>'80','gambar'=>'https://storage.googleapis.com/obrolantetangga/5nbpEmr1ns/obrolan/gambar/cTgrP3GmMRpf.jpg'],
  ['id'=>'81','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/alfinN2jLh.png'],
  ['id'=>'82','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/alfinnBKrQ.png'],
  ['id'=>'83','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/alfin0nlTL.png'],
  ['id'=>'84','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/alfinGVmeI.png'],
  ['id'=>'85','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/alfinGpatr.png'],
  ['id'=>'86','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/alfinBxXA2.png'],
  ['id'=>'87','gambar'=>'https://storage.googleapis.com/obrolantetangga/5nbpEmr1ns/obrolan/gambar/SBN_RGnIv4uF.jpg'],
  ['id'=>'88','gambar'=>'https://storage.googleapis.com/obrolantetangga/nTSbbWc70v/obrolan/gambar/nTSbbWc70v01NNj.mp4'],
  ['id'=>'89','gambar'=>'https://storage.googleapis.com/obrolantetangga/nTSbbWc70v/obrolan/gambar/nTSbbWc70vskhj7.jpeg'],
  ['id'=>'90','gambar'=>'https://storage.googleapis.com/obrolantetangga/nTSbbWc70v/obrolan/gambar/nTSbbWc70vtNpRr.mp4'],
  ['id'=>'91','gambar'=>'https://storage.googleapis.com/obrolantetangga/nTSbbWc70v/obrolan/gambar/nTSbbWc70viTWaQ.jpeg'],
  ['id'=>'92','gambar'=>'https://storage.googleapis.com/obrolantetangga/nTSbbWc70v/obrolan/gambar/nTSbbWc70vEZ3nw.jpeg'],
  ['id'=>'93','gambar'=>'https://storage.googleapis.com/obrolantetangga/5nbpEmr1ns/obrolan/gambar/732mP9CXyEKB.jpg'],
  ['id'=>'94','gambar'=>'https://storage.googleapis.com/obrolantetangga/kJbM5VHRMR/obrolan/gambar/HoVkT1uQqNme.jpg'],
  ['id'=>'95','gambar'=>'https://storage.googleapis.com/obrolantetangga/nTSbbWc70v/obrolan/gambar/b4fL1i_ktxge.jpg'],
  ['id'=>'96','gambar'=>'https://storage.googleapis.com/obrolantetangga/nTSbbWc70v/obrolan/gambar/nTSbbWc70vsK5pz.webm'],
  ['id'=>'97','gambar'=>'https://storage.googleapis.com/obrolantetangga/nTSbbWc70v/obrolan/gambar/nTSbbWc70vzxemx.webm'],
  ['id'=>'98','gambar'=>'https://storage.googleapis.com/obrolantetangga/nTSbbWc70v/obrolan/gambar/nTSbbWc70vBFcvC.mp4'],
  ['id'=>'103','gambar'=>'https://storage.googleapis.com/obrolantetangga/Zd7SZmWljN/obrolan/gambar/xVhHD0Ebf4kd.jpg'],
  ['id'=>'104','gambar'=>'https://storage.googleapis.com/obrolantetangga/Zd7SZmWljN/obrolan/gambar/IEnsJR8vfAbe.jpg'],
  ['id'=>'105','gambar'=>'https://storage.googleapis.com/obrolantetangga/Zd7SZmWljN/obrolan/gambar/BIGJcy0nYCf3.jpg'],
  ['id'=>'106','gambar'=>'https://storage.googleapis.com/obrolantetangga/Zd7SZmWljN/obrolan/gambar/zjJL_2Md0VgD.png'],
  ['id'=>'107','gambar'=>'https://storage.googleapis.com/obrolantetangga/Zd7SZmWljN/obrolan/gambar/IC3Rg4TwfoKk.jpg'],
  ['id'=>'108','gambar'=>'https://storage.googleapis.com/obrolantetangga/zIaxNvSwMA/obrolan/gambar/i4ndrJQLIvhe.jpg'],
  ['id'=>'109','gambar'=>'https://storage.googleapis.com/obrolantetangga/zIaxNvSwMA/obrolan/gambar/PbL_WD84ymeJ.jpg'],
  ['id'=>'110','gambar'=>'https://storage.googleapis.com/obrolantetangga/zIaxNvSwMA/obrolan/gambar/cE3fY_gT1L5z.jpg'],
  ['id'=>'111','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/wYPg5A4rxXFUqkH.jpeg'],
  ['id'=>'112','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/jZDKd_bmQO2R.jpg'],
  ['id'=>'113','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/1k9EqAQyHsXG.jpg'],
  ['id'=>'114','gambar'=>'https://storage.googleapis.com/obrolantetangga/5nbpEmr1ns/obrolan/gambar/5hyMuaizmxWd.jpg'],
  ['id'=>'115','gambar'=>'https://storage.googleapis.com/obrolantetangga/nTSbbWc70v/obrolan/gambar/nTSbbWc70v61E5C.jpeg'],
  ['id'=>'116','gambar'=>'https://storage.googleapis.com/obrolantetangga/5nbpEmr1ns/obrolan/gambar/alE741LP3yzn.jpg'],
  ['id'=>'117','gambar'=>'https://storage.googleapis.com/obrolantetangga/gKuVopHhBW/obrolan/gambar/ctl6LXSb3TZQ.jpg'],
  ['id'=>'118','gambar'=>'https://storage.googleapis.com/obrolantetangga/zIaxNvSwMA/obrolan/gambar/OPBdFtRxhLUi.jpg'],
  ['id'=>'119','gambar'=>'https://storage.googleapis.com/obrolantetangga/zIaxNvSwMA/obrolan/gambar/EDkiHw2_zcMj.jpg'],
  ['id'=>'120','gambar'=>'https://storage.googleapis.com/obrolantetangga/zIaxNvSwMA/obrolan/gambar/_97HmU802E1o.jpg'],
  ['id'=>'121','gambar'=>'https://storage.googleapis.com/obrolantetangga/Zd7SZmWljN/obrolan/gambar/uUlQ4qD8FKEo.jpg'],
  ['id'=>'122','gambar'=>'https://storage.googleapis.com/obrolantetangga/Zd7SZmWljN/obrolan/gambar/tvHbXLUmzkE0.jpg'],
  ['id'=>'123','gambar'=>'https://storage.googleapis.com/obrolantetangga/zIaxNvSwMA/obrolan/gambar/DxwAZoatFuU8.jpg'],
  ['id'=>'124','gambar'=>'https://storage.googleapis.com/obrolantetangga/Zd7SZmWljN/obrolan/gambar/3Q6KvPBAhIl0.jpg'],
  ['id'=>'125','gambar'=>'https://storage.googleapis.com/obrolantetangga/Zd7SZmWljN/obrolan/gambar/pn5PwfiEqQH1.jpg'],
  ['id'=>'126','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/kiqVZd4aH1Iv.jpg'],
  ['id'=>'127','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/8XvaBtPKhiFR.jpg'],
  ['id'=>'128','gambar'=>'https://storage.googleapis.com/obrolantetangga/zIaxNvSwMA/obrolan/gambar/whiNb1Kan_6u.jpg'],
  ['id'=>'129','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/UK7fYBmqNPOx.jpg'],
  ['id'=>'130','gambar'=>'https://storage.googleapis.com/obrolantetangga/1fiS4ZVEQb/obrolan/gambar/1fiS4ZVEQbmXPTo.jpeg'],
  ['id'=>'131','gambar'=>'https://storage.googleapis.com/obrolantetangga/1fiS4ZVEQb/obrolan/gambar/1fiS4ZVEQbRVqkF.jpeg'],
  ['id'=>'132','gambar'=>'https://storage.googleapis.com/obrolantetangga/1fiS4ZVEQb/obrolan/gambar/1fiS4ZVEQbcGcHl.mp4'],
  ['id'=>'133','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/Bae6PXgKR_SC.jpg'],
  ['id'=>'134','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/wYPg5A4rxXbSkBw.jpeg'],
  ['id'=>'135','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/wYPg5A4rxXWM1qM.jpeg'],
  ['id'=>'136','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/wYPg5A4rxXNORNh.jpeg'],
  ['id'=>'137','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/wYPg5A4rxXxwxy1.jpeg'],
  ['id'=>'138','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/wYPg5A4rxXeEFLC.jpeg'],
  ['id'=>'139','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/alfin1w8vr.jpeg'],
  ['id'=>'140','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/alfinUAVKC.jpeg'],
  ['id'=>'141','gambar'=>'https://storage.googleapis.com/obrolantetangga/gKuVopHhBW/obrolan/gambar/gKuVopHhBWK708F.png'],
  ['id'=>'142','gambar'=>'https://storage.googleapis.com/obrolantetangga/nTSbbWc70v/obrolan/gambar/nTSbbWc70vsGigR.jpeg'],
  ['id'=>'143','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/wYPg5A4rxXFhJdk.jpeg'],
  ['id'=>'144','gambar'=>'https://storage.googleapis.com/obrolantetangga/nTSbbWc70v/obrolan/gambar/nTSbbWc70vuGyp1.jpeg'],
  ['id'=>'145','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/wYPg5A4rxXfQy4W.jpeg'],
  ['id'=>'146','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/wYPg5A4rxXOxrys.jpeg'],
  ['id'=>'147','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/alfinigDdT.mp4'],
  ['id'=>'148','gambar'=>'https://storage.googleapis.com/obrolantetangga/alfin/obrolan/gambar/alfin17rUj.jpeg'],
  ['id'=>'149','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/wYPg5A4rxXbAhST.jpeg'],
  ['id'=>'150','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/wYPg5A4rxXNfHO4.jpeg'],
  ['id'=>'151','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/wYPg5A4rxXfbeST.jpeg'],
  ['id'=>'152','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/wYPg5A4rxXy8FOB.jpeg'],
  ['id'=>'153','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/wYPg5A4rxXA1cqp.jpeg'],
  ['id'=>'155','gambar'=>'https://storage.googleapis.com/obrolantetangga/gKuVopHhBW/obrolan/gambar/gKuVopHhBWU1ulA.jpeg'],
  ['id'=>'156','gambar'=>'https://storage.googleapis.com/obrolantetangga/wYPg5A4rxX/obrolan/gambar/wYPg5A4rxXdjmh2.jpeg'],
  ['id'=>'157','gambar'=>'https://storage.googleapis.com/obrolantetangga/jkm3OLr97x/obrolan/gambar/jkm3OLr97xE1MiZ.jpeg'],
  ['id'=>'158','gambar'=>'https://storage.googleapis.com/obrolantetangga/nTSbbWc70v/obrolan/gambar/nTSbbWc70vGt8y5.jpeg'],
  ['id'=>'159','gambar'=>'https://storage.googleapis.com/obrolantetangga/nTSbbWc70v/obrolan/gambar/nTSbbWc70v8QKS3.jpeg'],
  ['id'=>'160','gambar'=>'https://storage.googleapis.com/obrolantetangga/nTSbbWc70v/obrolan/gambar/nTSbbWc70vgpL91.mp4'],
  ['id'=>'161','gambar'=>'https://storage.googleapis.com/obrolantetangga/Odzfa0mVh3/obrolan/gambar/Odzfa0mVh3aerCK.png'],
  ['id'=>'162','gambar'=>'https://storage.googleapis.com/obrolantetangga/Odzfa0mVh3/obrolan/gambar/Odzfa0mVh3hejOs.png'],
];

$dx = [];
foreach ($datas as $key => $data) {
  try {
    ObrolanGambar::find($data['id'])->update(['gambar'=>$data['gambar']]);
    // print($data['id']);
  } catch (\Exception $e) {
    $dx[] = $data;
  }

  // print_r($data['id']);
  // print_r($data['gambar']);
}

return $dx;


    $url = "https://storage.googleapis.com/obrolantetangga";
    $disk = Storage::disk('gcs');
     $obrolans =  Obrolan::with('user')->get();

   // $users =  User::get();

     // ->with('obrolan_gambar')->with('obrolan_video')
    // ->where('user_id',1)

    foreach ($obrolans as $obrolan) {
      // print("{$user->unik_user}");

      // foreach ($obrolan->user as $user) {

        // if (!file_exists($disk->path("{$user->unik_user}"))) {
        //   $disk->makeDirectory("{$user->unik_user}");
        // }
        //
        // if (!file_exists($disk->path("{$user->unik_user}/obrolan"))) {
        //   $disk->makeDirectory("{$user->unik_user}/obrolan");
        // }
        //
        // if (!file_exists($disk->path("{$user->unik_user}/obrolan/gambar"))) {
        //   $disk->makeDirectory("{$user->unik_user}/obrolan/gambar");
        // }
        //
        // if (!file_exists($disk->path("{$user->unik_user}/obrolan/video"))) {
        //   $disk->makeDirectory("{$user->unik_user}/obrolan/video");
        // }

        // print_r($gambar->gambar);
        // echo "<br>";
      // }


      foreach ($obrolan->obrolan_gambar as $gambar) {
        Storage::disk('gcs')->put("{$obrolan->user->unik_user}/obrolan/gambar/{$gambar->gambar}", Storage::disk('public')->get("obrolan/{$gambar->gambar}"));
        // print_r($gambar->gambar);
        echo "ObrolanGambar::create(['id'=>'{$gambar->id}','gambar'=>'{$url}/{$obrolan->user->unik_user}/obrolan/gambar/{$gambar->gambar}']);<br>";
      }

      // foreach ($obrolan->obrolan_video as $video) {
      //   Storage::disk('gcs')->put("{$obrolan->user->unik_user}/obrolan/video/{$video->video}", Storage::disk('public')->get("obrolan/{$video->video}"));
      //   print_r($video->video);
      //   echo "<br>";
      // }
    }
    // return $als = User::select('cover','avatar','id')
    // ->where('avatar','like','%storage.googleapis%')
    // ->orWhere('cover','like','%storage.googleapis%')
    // ->get()->toJson();

    /*
    $profils = \json_decode('[{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/alfin\/cover\/alfin.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/alfin\/avatar\/alfin.png","id":1},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/gKuVopHhBW\/cover\/gKuVopHhBW.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/gKuVopHhBW\/avatar\/gKuVopHhBW.png","id":2},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/hTNXrpdyRn\/cover\/hTNXrpdyRn.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/hTNXrpdyRn\/avatar\/hTNXrpdyRn.png","id":3},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/wYPg5A4rxX\/cover\/wYPg5A4rxX.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/wYPg5A4rxX\/avatar\/wYPg5A4rxX.png","id":4},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/mA6cbp4Iep\/cover\/mA6cbp4Iep.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/mA6cbp4Iep\/avatar\/mA6cbp4Iep.png","id":5},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/knAV5Vfflz\/cover\/knAV5Vfflz.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/knAV5Vfflz\/avatar\/knAV5Vfflz.png","id":6},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/216yybW8Kf\/cover\/216yybW8Kf.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/216yybW8Kf\/avatar\/216yybW8Kf.png","id":7},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/ZoJ9w1uYOR\/avatar\/ZoJ9w1uYOR.png","id":8},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/tA3tawnbhM\/cover\/tA3tawnbhM.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/tA3tawnbhM\/avatar\/tA3tawnbhM.png","id":9},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/Zd7SZmWljN\/cover\/Zd7SZmWljN.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/Zd7SZmWljN\/avatar\/Zd7SZmWljN.png","id":10},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/sg9k1Dh4mt\/avatar\/sg9k1Dh4mt.png","id":11},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/w74N0svrTs\/avatar\/w74N0svrTs.png","id":13},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/WGia3Tv3Ye\/cover\/WGia3Tv3Ye.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/WGia3Tv3Ye\/avatar\/WGia3Tv3Ye.png","id":14},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/y1sqej8nhX\/avatar\/y1sqej8nhX.png","id":15},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/FAVQjKW4Zt\/avatar\/FAVQjKW4Zt.png","id":16},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/mDGREjwYp2\/avatar\/mDGREjwYp2.png","id":17},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/2TlemE362L\/avatar\/2TlemE362L.png","id":18},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/mSzrgMOaf2\/cover\/mSzrgMOaf2.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/mSzrgMOaf2\/avatar\/mSzrgMOaf2.png","id":19},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/lEKxWZjLPq\/avatar\/lEKxWZjLPq.png","id":21},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/sabqI3TvnS\/cover\/sabqI3TvnS.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/sabqI3TvnS\/avatar\/sabqI3TvnS.png","id":22},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/WkPMnAcyOV\/cover\/WkPMnAcyOV.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/WkPMnAcyOV\/avatar\/WkPMnAcyOV.png","id":23},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/nMYmG779nT\/avatar\/nMYmG779nT.png","id":25},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/TYfMQrm3eh\/avatar\/TYfMQrm3eh.png","id":26},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/qeHhzMkvcE\/avatar\/qeHhzMkvcE.png","id":28},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/7F6n47AJOF\/cover\/7F6n47AJOF.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/7F6n47AJOF\/avatar\/7F6n47AJOF.png","id":29},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/Le0ne6M0R8\/avatar\/Le0ne6M0R8.png","id":30},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/nGy0Rl2x9x\/cover\/nGy0Rl2x9x.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/nGy0Rl2x9x\/avatar\/nGy0Rl2x9x.png","id":31},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/WRxFWh1DFX\/avatar\/WRxFWh1DFX.png","id":32},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/lVijBsLy5K\/cover\/lVijBsLy5K.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/lVijBsLy5K\/avatar\/lVijBsLy5K.png","id":33},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/lMqFEyCyg6\/cover\/lMqFEyCyg6.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/lMqFEyCyg6\/avatar\/lMqFEyCyg6.png","id":34},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/zCZbNPM0ni\/cover\/zCZbNPM0ni.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/zCZbNPM0ni\/avatar\/zCZbNPM0ni.png","id":35},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/YO4iD4uxs8\/avatar\/YO4iD4uxs8.png","id":36},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/ngVNZTzvwl\/avatar\/ngVNZTzvwl.png","id":37},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/14g17sTNyJ\/avatar\/14g17sTNyJ.png","id":38},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/RIkqd8FGp3\/avatar\/RIkqd8FGp3.png","id":39},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/OR7vrtdjW4\/avatar\/OR7vrtdjW4.png","id":40},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/5nbpEmr1ns\/cover\/5nbpEmr1ns.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/5nbpEmr1ns\/avatar\/5nbpEmr1ns.png","id":42},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/Xs3RHSdtzs\/avatar\/Xs3RHSdtzs.png","id":43},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/jU5YvrT4VQ\/avatar\/jU5YvrT4VQ.png","id":44},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/nTSbbWc70v\/cover\/nTSbbWc70v.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/nTSbbWc70v\/avatar\/nTSbbWc70v.png","id":51},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/t3RYlo7n9C\/avatar\/t3RYlo7n9C.png","id":53},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/12tbDcgGdK\/avatar\/12tbDcgGdK.png","id":54},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/ecyu0xzchQ\/avatar\/ecyu0xzchQ.png","id":56},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/aIXsQb1sCG\/avatar\/aIXsQb1sCG.png","id":57},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/j5pl5LrdOp\/avatar\/j5pl5LrdOp.png","id":58},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/Zwoixcdze9\/avatar\/Zwoixcdze9.png","id":59},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/jqQWucdkm7\/avatar\/jqQWucdkm7.png","id":64},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/sY3D1fDU0n\/avatar\/sY3D1fDU0n.png","id":65},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/1fiS4ZVEQb\/avatar\/1fiS4ZVEQb.png","id":71},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/3w5wi0SFzp\/avatar\/3w5wi0SFzp.png","id":77},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/kJbM5VHRMR\/avatar\/kJbM5VHRMR.png","id":78},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/Ct8QTkpF11\/avatar\/Ct8QTkpF11.png","id":79},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/zIaxNvSwMA\/avatar\/zIaxNvSwMA.png","id":80},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/P7YfDhxOQE\/avatar\/P7YfDhxOQE.png","id":81},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/SChzwAJHkK\/avatar\/SChzwAJHkK.png","id":82},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/WOAorESk91\/avatar\/WOAorESk91.png","id":83},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/pGw7kYJKPm\/avatar\/pGw7kYJKPm.png","id":84},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/5fDoYsUcOa\/avatar\/5fDoYsUcOa.png","id":85},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/dJ0SzTeMJg\/avatar\/dJ0SzTeMJg.png","id":87},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/loWJ8lYuyE\/cover\/loWJ8lYuyE.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/loWJ8lYuyE\/avatar\/loWJ8lYuyE.png","id":88},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/xpOn87QIEK\/avatar\/xpOn87QIEK.png","id":89},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/Z8GCwnHUqA\/avatar\/Z8GCwnHUqA.png","id":90},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/NVvAcGWhyi\/avatar\/NVvAcGWhyi.png","id":91},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/TBqRm53E1X\/avatar\/TBqRm53E1X.png","id":92},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/5fpKiYSnb7\/cover\/5fpKiYSnb7.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/5fpKiYSnb7\/avatar\/5fpKiYSnb7.png","id":93},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/iSbZJ3PTVw\/cover\/iSbZJ3PTVw.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/iSbZJ3PTVw\/avatar\/iSbZJ3PTVw.png","id":94},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/7MOt1gxTb9\/cover\/7MOt1gxTb9.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/7MOt1gxTb9\/avatar\/7MOt1gxTb9.png","id":95},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/8ipeys5JGi\/cover\/8ipeys5JGi.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/8ipeys5JGi\/avatar\/8ipeys5JGi.png","id":96},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/meqPwhtaMe\/avatar\/meqPwhtaMe.png","id":100},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/86I31iG8I7\/avatar\/86I31iG8I7.png","id":101},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/yAPQ9lkPSe\/cover\/yAPQ9lkPSe.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/yAPQ9lkPSe\/avatar\/yAPQ9lkPSe.png","id":102},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/RoSM8Alcd0\/cover\/RoSM8Alcd0.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/RoSM8Alcd0\/avatar\/RoSM8Alcd0.png","id":103},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/43rewhp6O4\/avatar\/43rewhp6O4.png","id":104},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/wnMmLIvtfC\/cover\/wnMmLIvtfC.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/wnMmLIvtfC\/avatar\/wnMmLIvtfC.png","id":105},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/AjmUPRLkbc\/avatar\/AjmUPRLkbc.png","id":106},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/VHjObMAwCg\/avatar\/VHjObMAwCg.png","id":107},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/8oYYJmx8oo\/avatar\/8oYYJmx8oo.png","id":111},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/jkm3OLr97x\/cover\/jkm3OLr97x.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/jkm3OLr97x\/avatar\/jkm3OLr97x.png","id":119},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/37FsmyBiZm\/avatar\/37FsmyBiZm.png","id":120},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/dL2zA2gmEQ\/avatar\/dL2zA2gmEQ.png","id":121},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/zPJJb9WFHF\/avatar\/zPJJb9WFHF.png","id":122},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/mdy7foTr68\/avatar\/mdy7foTr68.png","id":123},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/JSH8eLxXXW\/avatar\/JSH8eLxXXW.png","id":124},{"cover":"https:\/\/storage.googleapis.com\/obrolantetangga\/Odzfa0mVh3\/cover\/Odzfa0mVh3.png","avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/Odzfa0mVh3\/avatar\/Odzfa0mVh3.png","id":125},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/D4cdqpjZCh\/avatar\/D4cdqpjZCh.png","id":126},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/iqYEvM1kVA\/avatar\/iqYEvM1kVA.png","id":127},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/kJp5XrHZo5\/avatar\/kJp5XrHZo5.png","id":129},{"avatar":"https:\/\/storage.googleapis.com\/obrolantetangga\/fvWjzVbJ5O\/avatar\/fvWjzVbJ5O.png","id":130}]');

    $error = [];
    foreach ($profils as $key => $profil) {
      try {
        $xxuser = User::find($profil->id);
        if ($xxuser) {
          if (isset($profil->cover)){
            $xxuser->update(['cover'=>$profil->cover]);
          }

          if (isset($profil->avatar)){
            $xxuser->update(['avatar'=>$profil->avatar]);
          }

        }else {
          $error[] = $profil;
        }

      } catch (\Exception $e) {
        $error[] = $e->getMessage();
      }

    }

    return $error;
    // cuma nambah ini doank

    // return User::all();

    //*/

    /*

    // unik_user
    // avatar

    $disk = Storage::disk('gcs');
    $data = [];
    //
      // $directory = "alfin_new";
      // return Storage::disk('gcs')->url('alfin.txt');

    $url = "https://storage.googleapis.com/obrolantetangga";
    $users =  User::whereNotNull('avatar')->get();



    foreach ($users as $key => $user) {

      if (!file_exists($disk->path("{$user->unik_user}"))) {
        $disk->makeDirectory("{$user->unik_user}");
      }

      if (!file_exists($disk->path("{$user->unik_user}/avatar"))) {
        $disk->makeDirectory("{$user->unik_user}/avatar");
      }

      try {
        Storage::disk('gcs')->put("{$user->unik_user}/avatar/{$user->unik_user}.png", Storage::disk('public')->get($user->avatar));
        $user->update(['avatar'=>"{$url}/{$user->unik_user}/avatar/{$user->unik_user}.png"]);
        ['error'=>null,'user'=>$user,'avatar'=>"{$url}/{$user->unik_user}/avatar/{$user->unik_user}.png"];
      } catch (\Exception $e) {
        $data[] = ['error'=>$e->getMessage(),'user'=>$user,'avatar'=>"{$url}/{$user->unik_user}/avatar/{$user->unik_user}.png"];
      }

      // code...
    }
    return $data;

    //*/
  }
}
