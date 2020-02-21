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
        // $this->call(UsersTableSeeder::class);
        $user = new \App\User();
        $user->name = 'DimX';
        $user->password = \Illuminate\Support\Facades\Hash::make('qaz593952');
        $user->email = 'dimadimx@gmail.com';
        $user->api_user = 'DimX';
        $user->api_password = 'qaz593952';
        $user->api_uid = 16;
        $user->api_gid = 6;
        $user->price = 25;
        $user->email_verified_at = now();
        $user->remember_token = \Illuminate\Support\Str::random(10);
        $user->save();
    }
}
