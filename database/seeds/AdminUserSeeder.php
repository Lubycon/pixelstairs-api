<?php

use Illuminate\Database\Seeder;
use App\Models\AccessToken;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        $admin = [
            [
            	'email'=>'admin@pixelstairs.com',
                'nickname' => 'Admin',
            	'password' => bcrypt(env('COMMON_PASSWORD')),
                'status' => 'active',
            	'grade' => 'super_admin',
                'birthday' => '1993-10-27 00:00:00',
                'gender' => "male",
                'image_id' => 2,
                'newsletters_accepted' => true,
                'terms_of_service_accepted' => true,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'email'=>'test@pixelstairs.com',
                'nickname' => 'TestUser',
                'password' => bcrypt(env('TEST_PASSWORD')),
                'status' => 'active',
                'grade' => 'general',
                'birthday' => '1993-10-27 00:00:00',
                'gender' => "male",
                'image_id' => 3,
                'newsletters_accepted' => true,
                'terms_of_service_accepted' => true,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'email'=>'bboydart91@gmail.com',
                'nickname' => 'Evan',
                'password' => bcrypt(env('COMMON_PASSWORD')),
                'status' => 'active',
                'grade' => 'admin',
                'birthday' => '1991-10-27 00:00:00',
                'gender' => "male",
                'image_id' => null,
                'newsletters_accepted' => true,
                'terms_of_service_accepted' => true,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'email'=>'bboyzepot@gmail.com',
                'nickname' => 'daniel_zepp',
                'password' => bcrypt(env('COMMON_PASSWORD')),
                'status' => 'active',
                'grade' => 'admin',
                'birthday' => '1993-10-27 00:00:00',
                'gender' => "male",
                'image_id' => null,
                'newsletters_accepted' => true,
                'terms_of_service_accepted' => true,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
        ];
        DB::table('users')->insert($admin);


        Auth::onceUsingId(2);
        AccessToken::createToken();
        Auth::user()->token()->first()->update([
            "token" => "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA1NTQxMDE5LCJleHAiOjE1MDU1NDQ2MTksIm5iZiI6MTUwNTU0MTAxOSwianRpIjoiekFwOWlUSmdjTlBOYnRociJ9.NdK7NHJ98U3nMqSraJMpnr10cd1cz3EbZHyaFLWMlKc",
        ]);
    }
}
