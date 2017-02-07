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

        $json = File::get("database/seeds/json/category.json");
        $category = json_decode($json,true);

        $json = File::get("database/seeds/json/division.json");
        $division = json_decode($json,true);


        DB::table('categories')->insert($category);
        DB::table('divisions')->insert($division);
    }
}
