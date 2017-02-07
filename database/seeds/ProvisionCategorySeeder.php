<?php

use Illuminate\Database\Seeder;

class ProvisionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->truncate();
        $jsonCate = File::get("database/seeds/json/category.json");
        $category = json_decode($jsonCate,true);
        DB::table('categories')->insert($category);


        DB::table('divisions')->truncate();
        $jsonDivi = File::get("database/seeds/json/division.json");
        $division = json_decode($jsonDivi,true);
        DB::table('divisions')->insert($division);


        DB::table('review_question_keys')->truncate();
        $json = File::get("database/seeds/json/review_question_key.json");
        $division = json_decode($json,true);
        DB::table('review_question_keys')->insert($division);
    }
}
