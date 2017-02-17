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
        $admin = array(
            array(
            	'email'=>'admin@mittycompany.com',
                'phone' => '01089954868',
            	'name' => 'Admin',
                'nickname' => 'Admin',
            	'password' => bcrypt(env('COMMON_PASSWORD')),
                'remember_token' => 'wmLRmEIui4DtFz5ikU5mZ6Cm2gKCIOW1',
                'status' => 'active',
            	'grade' => 'superAdmin',
                'position' => 'admin_account',
                'gender_id' => 1,
                'birthday' => '1993-10-27 00:00:00',
                'country_id' => 201,
                'city' => 'seoul',
                'address1' => 'gangbuck',
                'address2' => 'mia',
                'post_code' => '12312',
                'image_id' => 2,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'email'=>'peter@mittycompany.com',
                'phone' => '01025693773',
                'name' => '오주현',
                'nickname' => 'Peter',
                'password' => bcrypt('ojh770700'),
                'remember_token' => 'wmLRmEIui4DtFz5ikU5mZ6Cm2gKCIOW2',
                'status' => 'active',
                'grade' => 'admin',
                'position' => 'CEO',
                'gender_id' => 1,
                'birthday' => '1993-10-27 00:00:00',
                'country_id' => 201,
                'city' => 'seoul',
                'address1' => 'hap-jung',
                'address2' => 'street',
                'post_code' => '5483',
                'image_id' => 3,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ),
        );

        DB::table('users')->insert($admin);
    }
}
