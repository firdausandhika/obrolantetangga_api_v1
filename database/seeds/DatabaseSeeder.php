<?php

use Illuminate\Database\Seeder;

use App\Model\IklanDefault;
use App\Model\User;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      // \DB::table('admins')->insert([
      //     'username' => 'admin',
      //     'email' => 'admin@gmail.com',
      //     'password' => Hash::make('admin'),
      // ]);
      //82394613156
      // IklanDefault::whereIn('id',[2,4])->update([
      //   'foto_iklan'=>"https://storage.googleapis.com/obrolantetangga/iklan_defaults/banner%20surasama.jpg"
      // ]);

      User::where('phone','LIKE','%85393366485')->update(['phone'=>'853933664852']);
    }
}
