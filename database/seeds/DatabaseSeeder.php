<?php

use Illuminate\Database\Seeder;

use App\Model\IklanDefault;
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

      IklanDefault::whereIn('id',[2,4])->update([
        'foto_iklan'=>"https://00f74ba44b59d8246cdfa3a51ea6d0cff342d0bed8-apidata.googleusercontent.com/download/storage/v1/b/obrolantetangga/o/iklan_defaults%2Fbanner%20surasama.jpg?jk=AFshE3XQbLMQGttBtNnFM4lgYcziEwMoTz4E4bmNbFJPF-wyMMAGLNE8cikRtSdYq0R9DH5qCWTvFxFLQAKvIJ02ynEs0AsmEJvHdbWP_JDV4COGoNRYsghB7PXRQNUEu1Z25iNuS3Vudhb2HJundJoZdZ5Aq1JwPFrGCPl7q2He9SQgMP0Cmxx3lgBuwYRK5V1aryE3rCC-5rwNiQvJFu0MwtAlTe0bfb9rVgBF4zmx5vn7X06_gdvFAst-UwDfA4Jol7ZpWv237HIOke5HxFRhMKb9wSDr0U67V8f2EJZJBuqpzqpXxHfADqhu5X53eXcARqnkcj-qIu4oqIcG9IfSYRcyqEHH5Phxev_WCK6HOZ_dS4K5vGChP72XTLl5OpPuwFgL7Uks6gb1-oMzgRhPuxEJNXyIhoeaavUNKNxRTwXOhmKGjyV9ZYle0RchG8VgnPfJUfj615FyMyO3TijmUuKL_8e3d8aXzKYj0x2rmnBocHbSVmV7f70QBGJedbao6wO6Plrlql_DY5SCOY2DV3Bq2WaANs6wWWbeiTupwv6Y5CZxlL5r3uNJcyJOOgLqjCjP6kKXMravGqymZqeciGc3vb-8CxqelOTMP1tgXuvgQAXZyCbQzJo2HjagpEliSduNW7iXbALX5oV8L6owWOQPo84CWPOA1Z0zj_C4r2Z8R0sLt6WegaopswbAK9UXdgmTeujMAcmDmyvkDkzE1g7U9gBWmaP62YffRbZJzDrDBhkBLMxV59JSoXkFBd76vcVtl42jqYrXIMJbivp0GrldtB8LYQH-cpkTFSGh2dKbTo60RFICrQ4-FYfvwusAUY80Wpn3YEBr1KLmaCgvc1yUwQtt7DWuy3hsRW7PpCcBjj3vN7zOA4_8JP_T2U3UuqnKLrXASwyIFtcPO02iyEMS4_t8bZWE1qVWwjCfATU0j49wM9uMR_WyE81FLN2gWVjdFqX6W6DnE_A2PcaCpNdMy4A50q1bq3XkFw0&isca=1"
      ]);
    }
}
