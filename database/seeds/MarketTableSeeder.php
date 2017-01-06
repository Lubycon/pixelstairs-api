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
            ),
            array(
                'code' => '0101',
            	'name' => 'gmarket',
            ),
            array(
                'code' => '0102',
            	'name' => 'auction',
            ),
        );

        DB::table('markets')->insert($market);
    }
}
