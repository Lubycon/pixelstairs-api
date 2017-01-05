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
            	'id'=>'1',
            	'name' => '11st',
            ),
            array(
            	'id'=>'2',
            	'name' => 'gmarket',
            ),
        );

        DB::table('markets')->insert($market);
    }
}
