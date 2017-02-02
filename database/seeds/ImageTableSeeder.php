<?php

use Illuminate\Database\Seeder;

class ImageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('images')->truncate();
        $market = array(
            array(
                "id" => 1,
                "url" => env('S3_PATH').env('USER_DEFAULT_IMG_URL'),
                "is_mitty_own" => false,
            ),
            array(
                "id" => 2,
                "url" => "http://cfile22.uf.tistory.com/image/234D193557557D87304A42",
                "is_mitty_own" => false,
            ),
            array(
                "id" => 3,
                "url" => "https://s-media-cache-ak0.pinimg.com/736x/26/c3/9d/26c39d418a99648a6c2cbbe4c5bd5d55.jpg",
                "is_mitty_own" => false,
            )
        );

        DB::table('images')->insert($market);
    }
}
