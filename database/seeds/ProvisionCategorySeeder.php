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
        DB::table('divisions')->truncate();

        $jsonCate = File::get("database/seeds/json/category.json");
        $category = json_decode($jsonCate,true);

        $jsonDivi = File::get("database/seeds/json/division.json");
        $division = json_decode($jsonDivi,true);


        DB::table('categories')->insert($category);
        DB::table('divisions')->insert($division);
    }
}
