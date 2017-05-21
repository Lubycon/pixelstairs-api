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
        $this->call(ImageTableSeeder::class);
        if( env('APP_ENV') != 'production' ){
            $this->call(DummyDataSeeder::class);
        }
        $this->call(QuoteSeeder::class);

        Model::reguard();
    }
}
