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
            	'name' => '11st',
                'country_id' => 211,
            ),
            array(
                'code' => '0101',
            	'name' => 'gmarket',
                'country_id' => 211,
            ),
            array(
                'code' => '0102',
            	'name' => 'auction',
                'country_id' => 211,
            ),
        );

        DB::table('markets')->insert($market);
    }
}
