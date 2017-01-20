<?php

use Illuminate\Database\Seeder;

class GenderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genders')->truncate();
        $admin = array(
            array(
                'id' => 0,
                'translate_name_id' => 17,
            ),
            array(
                'id' => 1,
                'translate_name_id' => 18,
            ),
            array(
                'id' => 2,
                'translate_name_id' => 19,
            ),
        );
        DB::table('genders')->insert($admin);
    }
}
