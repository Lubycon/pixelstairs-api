<?php

use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('users')->truncate(); run this code in admin seeder
        // factory(App\Models\User::class, 100)->create();

        DB::table('categories')->truncate();
        factory(App\Models\Category::class, 30)->create();

        DB::table('divisions')->truncate();
        factory(App\Models\Division::class, 100)->create();

        DB::table('skus')->truncate();
        factory(App\Models\Sku::class, 3000)->create();

        DB::table('options')->truncate();
        factory(App\Models\Option::class, 3000)->create();

        DB::table('brands')->truncate();
        factory(App\Models\Brand::class, 100)->create();

        DB::table('products')->truncate();
        factory(App\Models\Product::class, 500)->create();
    }
}
