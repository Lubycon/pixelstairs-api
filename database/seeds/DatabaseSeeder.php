<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(AdminUserSeeder::class);
//        $this->call(ImageTableSeeder::class);
        $this->call(MarketTableSeeder::class);
        $this->call(CountryTableSeeder::class);
        $this->call(TranslateTableSeeder::class);
        $this->call(StatusTableSeeder::class);
        $this->call(GenderTableSeeder::class);
//        $this->call(DummyDataSeeder::class);
//        $this->call(RealProductSeed::class);

        Model::reguard();
    }
}
