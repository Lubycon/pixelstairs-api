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
        $market = [
            [
                "id" => 1,
                "url" => env('S3_PATH').env('USER_DEFAULT_IMG_URL'),
                "is_pixel_own" => false,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                "id" => 2,
                "url" => "http://cfile22.uf.tistory.com/image/234D193557557D87304A42",
                "is_pixel_own" => false,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
        ];

        DB::table('images')->insert($market);
    }
}
