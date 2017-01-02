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
            	'email'=>'lubycon@gmail.com',
            	'nickname' => 'Admin',
            	'password' => bcrypt('qwerqwer'),
                'remember_token' => 'wmLRmEIui4DtFz5ikU5mZ6Cm2gKCIOW1',
            	'country_id' => 201,
            	'occupation_id' => 1,
            	'status' => 'active',
            	'grade' => 'admin',
            	'newsletter' => true,
            	'profile_img' => null,
            	'company' => 'Lubycon co.',
            	'city' => 'Seoul',
            	'mobile' => '010-1234-1234',
            	'fax' => '123-4234-12458',
            	'web' => 'aws.lubycon.com',
            	'description' => 'this is administrator',
            	'email_public' => 'Public',
            	'mobile_public' => 'Public',
            	'fax_public' => 'Public',
            	'web_public' => 'Public',
            ),
        );

        DB::table('users')->insert($admin);
    }
}
