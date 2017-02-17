<?php

use Illuminate\Database\Seeder;

class MarketTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('markets')->truncate();
        $market = array(
            array(
                'code' => '0100',
            	'translate_name_id' => 1,
                'country_id' => 211,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'code' => '0101',
            	'translate_name_id' => 2,
                'country_id' => 211,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'code' => '0102',
            	'translate_name_id' => 3,
                'country_id' => 211,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'code' => '0103',
                'translate_name_id' => 23,
                'country_id' => 211,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ),
        );

        DB::table('markets')->insert($market);
    }
}
