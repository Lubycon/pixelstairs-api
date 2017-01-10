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
            	'name' => 'Admin',
                'nickname' => 'Admin',
            	'password' => bcrypt('alxlalxl0102'),
                'remember_token' => 'wmLRmEIui4DtFz5ikU5mZ6Cm2gKCIOW1',
            	'grade' => 'superAdmin',
                'position' => 'admin_account',
            ),
            array(
                'email'=>'peter@mittycompany.com',
                'name' => '오주현',
                'nickname' => 'Peter',
                'password' => bcrypt('ojh770700'),
                'remember_token' => 'wmLRmEIui4DtFz5ikU5mZ6Cm2gKCIOW1',
                'grade' => 'admin',
                'position' => 'CEO',
            ),
        );

        DB::table('users')->insert($admin);
    }
}
