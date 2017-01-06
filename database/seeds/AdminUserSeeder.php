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
            	'password' => bcrypt('alxlalxl0102'),
                'remember_token' => 'wmLRmEIui4DtFz5ikU5mZ6Cm2gKCIOW1',
            	'grade' => 'super_admin',
                'position' => 'admin',
            ),
        );

        DB::table('users')->insert($admin);
    }
}
