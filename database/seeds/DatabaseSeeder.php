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

        if( App::isLocal() ){
            $this->call(AdminUserSeeder::class);
            $this->call(ImageTableSeeder::class);
            $this->call(DummyDataSeeder::class);
        }
        $this->call(QuoteSeeder::class);
        $this->call(SigndropAnswerSeeder::class);
        $this->call(SigndropQuestionSeeder::class);

        Model::reguard();
    }
}
