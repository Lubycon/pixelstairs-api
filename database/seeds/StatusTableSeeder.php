<?php

use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
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
                'code' => '0300',
                'translate_name_id' => 4,
            ),
            array(
                'code' => '0301',
                'translate_name_id' => 5,
            ),
            array(
                'code' => '0302',
                'translate_name_id' => 6,
            ),
            array(
                'code' => '0310',
                'translate_name_id' => 7,
            ),
            array(
                'code' => '0311',
                'translate_name_id' => 8,
            ),
            array(
                'code' => '0312',
                'translate_name_id' => 9,
            ),
            array(
                'code' => '0313',
                'translate_name_id' => 10,
            ),
            array(
                'code' => '0314',
                'translate_name_id' => 11,
            ),
            array(
                'code' => '0315',
                'translate_name_id' => 12,
            ),
            array(
                'code' => '0316',
                'translate_name_id' => 13,
            ),
            array(
                'code' => '0317',
                'translate_name_id' => 14,
            ),
            array(
                'code' => '0318',
                'translate_name_id' => 15,
            ),
            array(
                'code' => '0319',
                'translate_name_id' => 16,
            ),

        );

        DB::table('statuses')->insert($data);
    }
}
