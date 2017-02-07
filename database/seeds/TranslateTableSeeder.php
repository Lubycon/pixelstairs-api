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

        $json = File::get("database/seeds/json/translate_name.json");
        $name = json_decode($json,true);

        DB::table('translate_names')->insert($name);
    }
}
