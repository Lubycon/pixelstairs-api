<?php

use Illuminate\Database\Seeder;

class UnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('units')->truncate();
        $data = array(
            array(
                'id' => 1,
                'translate_name_id' => 20,
            ),
            array(
                'id' => 2,
                'translate_name_id' => 21,
            ),
            array(
                'id' => 3,
                'translate_name_id' => 22,
            ),
        );

        DB::table('units')->insert($data);
    }
}
