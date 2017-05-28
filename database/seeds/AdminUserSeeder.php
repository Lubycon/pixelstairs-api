<?php

use Illuminate\Database\Seeder;

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
                'token' => 'wmLRmEIui4DtFz5ikU5mZ6Cm2gKCIOW1',
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
                'password' => bcrypt('password'),
                'token' => 'wtesttesttesttesttesttesttestte2',
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
                'password' => bcrypt('password'),
                'token' => 'wtesttesttesttesttesttesttestte3',
                'status' => 'active',
                'grade' => 'admin',
                'birthday' => '1992-10-27 00:00:00',
                'gender' => "male",
                'image_id' => null,
                'newsletters_accepted' => true,
                'terms_of_service_accepted' => true,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
        ];
        DB::table('users')->insert($admin);
    }
}
