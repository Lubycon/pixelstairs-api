<?php

use Illuminate\Database\Seeder;
use App\Models\Quote;

class QuoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('quotes')->truncate();
        $getJson = File::get("database/seeds/jsons/quotes.json");
        $json = json_decode($getJson,true);
        DB::table('quotes')->insert($json);
    }
}
