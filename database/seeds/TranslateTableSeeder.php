<?php

use Illuminate\Database\Seeder;

class TranslateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('translate_names')->truncate();
        $market = array(
            array(
                'id' => 1,
                'original' => '11번가',
                'chinese' => '11街',
                'korean' => '11번가',
                'english' => '11st',
            ),
            array(
                'id' => 2,
                'original' => 'Gmarket',
                'chinese' => 'G-市场',
                'korean' => '지마켓',
                'english' => 'Gmarket',
            ),
            array(
                'id' => 3,
                'original' => 'Auction',
                'chinese' => '拍卖',
                'korean' => '옥션',
                'english' => 'Auction',
            ),
        );

        DB::table('translate_names')->insert($market);
    }
}
