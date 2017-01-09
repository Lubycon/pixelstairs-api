<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

// $factory->define(App\Models\Category::class, function (Faker\Generator $faker) {
//     return [
//         'nickname' => $faker->name,
//         'email' => $faker->safeEmail,
//         'password' => bcrypt(str_random(10)),
//         'remember_token' => str_random(10),
//         'occupation_id' => mt_rand(1,5),
//         'country_id' => mt_rand(1,240),
//         'profile_img' => "http://lorempixel.com/640/640/",
//         'status' => 'active',
//         'newsletter' => mt_rand(0,1),
//     ];
// });

$factory->define(App\Models\Category::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name
    ];
});

$factory->define(App\Models\Division::class, function (Faker\Generator $faker) {
    return [
        'market_id' => 1,
        'market_category_id' => mt_rand(100000000,110000000),
        'name' => $faker->name,
        'parent_id' => mt_rand(1,30),
    ];
});

$factory->define(App\Models\Sku::class, function (Faker\Generator $faker) {
    return [
        'market_id' => 1,
        'product_id' => mt_rand(1,500),
        'sku' => $faker->name,
        'description' => $faker->name.','.$faker->name.','.$faker->name,
    ];
});

$factory->define(App\Models\Option::class, function (Faker\Generator $faker) {
    return [
        'product_id' => mt_rand(1,500),
        'sku_id' => $faker->safeEmail,
        'original_name' => str_random(10),
        'chinese_name' => str_random(10),
        'price' => mt_rand(10000,50000),
    ];
});

$factory->define(App\Models\Brand::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});

$factory->define(App\Models\Product::class, function (Faker\Generator $faker) {
    return [
        'product_id' => mt_rand(100000000,110000000),
        'category_id' => mt_rand(1,30),
        'division_id' => mt_rand(1,100),
        'market_id' => 1,
        'brand_id' => mt_rand(1,100),
        'original_title' => $faker->name,
        'chinese_title' => $faker->name,
        'description' => 'description_'.str_random(10),
        'price' => mt_rand(10000,50000),
        'domestic_delivery_price' => mt_rand(1000,2500),
        'is_free_delivery' => mt_rand(0,1),
        'url' => 'input_url',
        'status_code' => '0300',
        'end_date' => date("Y-m-d H:i:s",rand(1262055681,1478304000)),
    ];
});
