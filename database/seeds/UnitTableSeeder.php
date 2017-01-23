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
        DB::table('statuses')->truncate();
        $data = array(
            array(
                'id' => 1,
                'translate_name_id' => 4,
            ),
            array(
                'id' => 2,
                'translate_name_id' => 5,
            ),
            array(
                'id' => 3,
                'translate_name_id' => 6,
            ),
        );

        DB::table('statuses')->insert($data);
    }
}
