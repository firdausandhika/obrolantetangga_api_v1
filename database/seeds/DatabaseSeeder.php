<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      \DB::table('admins')->insert([
          'username' => 'admin',
          'email' => 'admin@gmail.com',
          'password' => Hash::make('admin'),
      ]);
    }
}
