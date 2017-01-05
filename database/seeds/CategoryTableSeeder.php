<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->truncate();
        $category = array(
            array(
                "id" => 1,
                "market_id" => 1,
                "name" => "브랜드패션",
            ),
            array(
                "id" => 2,
                "market_id" => 1,
                "name" => "의류",
            ),
            array(
                "id" => 3,
                "market_id" => 1,
                "name" => "잡화",
            ),
            array(
                "id" => 4,
                "market_id" => 1,
                "name" => "뷰티",
            ),
            array(
                "id" => 5,
                "market_id" => 1,
                "name" => "스포츠/레저",
            ),
            array(
                "id" => 6,
                "market_id" => 1,
                "name" => "자동차/공구",
            ),
            array(
                "id" => 7,
                "market_id" => 1,
                "name" => "식품",
            ),
            array(
                "id" => 8,
                "market_id" => 1,
                "name" => "출산/육아",
            ),
            array(
                "id" => 9,
                "market_id" => 1,
                "name" => "생필품/주방",
            ),
            array(
                "id" => 10,
                "market_id" => 1,
                "name" => "건강",
            ),
            array(
                "id" => 11,
                "market_id" => 1,
                "name" => "가구/인테리어",
            ),
            array(
                "id" => 12,
                "market_id" => 1,
                "name" => "가전",
            ),
            array(
                "id" => 13,
                "market_id" => 1,
                "name" => "컴퓨터",
            ),
            array(
                "id" => 14,
                "market_id" => 1,
                "name" => "디지털",
            ),
            array(
                "id" => 15,
                "market_id" => 1,
                "name" => "도서/문구",
            ),
            array(
                "id" => 16,
                "market_id" => 1,
                "name" => "여행/e쿠폰",
            ),
            array(
                "id" => 17,
                "market_id" => 1,
                "name" => "취미",
            ),
            array(
                "id" => 18,
                "market_id" => 1,
                "name" => "반려동물",
            ),
            array(
                "id" => 19,
                "market_id" => 1,
                "name" => "해외직구",
            ),
        );
        DB::table('categories')->insert($category);
    }
}
