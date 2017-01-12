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
        'original_name' => $faker->name,
        'chinese_name' => 'zh'.$faker->name
    ];
});

$factory->define(App\Models\Division::class, function (Faker\Generator $faker) {
    return [
        'original_name' => $faker->name,
        'chinese_name' => 'zh'.$faker->name,
        'parent_id' => mt_rand(1,30),
    ];
});

$factory->define(App\Models\Sector::class, function (Faker\Generator $faker) {
    return [
        'market_id' => "0100",
        'market_category_id' => mt_rand(100000000,110000000),
        'original_name' => $faker->name,
        'chinese_name' => 'zh'.$faker->name,
        'parent_id' => mt_rand(1,100),
    ];
});

$factory->define(App\Models\Sku::class, function (Faker\Generator $faker) {
    return [
        'market_id' => "0100",
        'product_id' => mt_rand(1,500),
        'sku' => 'MK001PD'.mt_rand(10000000,11000000).'ID'.mt_rand(0,100),
        'description' => $faker->name.','.$faker->name.','.$faker->name,
    ];
});

$factory->define(App\Models\Option::class, function (Faker\Generator $faker) {
    return [
        'product_id' => mt_rand(1,500),
        'sku_id' => mt_rand(1,3000),
        'original_name' => '옵션1'.',옵션2'.',옵션3',
        'chinese_name' => '选项1'.',选项2'.',选项3',
        'price' => mt_rand(10000,50000),
    ];
});

$factory->define(App\Models\Brand::class, function (Faker\Generator $faker) {
    return [
        'original_name' => 'original_'.$faker->name,
        'korean_name' => 'korean_'.$faker->name,
        'chinese_name' => 'chinese_'.$faker->name,
        'english_name' => 'english_'.$faker->name,
    ];
});

$factory->define(App\Models\Product::class, function (Faker\Generator $faker) {
    return [
        'product_id' => mt_rand(100000000,110000000),
        'haitao_product_id' => mt_rand(100000000,110000000),
        'category_id' => mt_rand(1,30),
        'division_id' => mt_rand(1,100),
        'sector_id_0' => mt_rand(1,300),
        'sector_id_1' => mt_rand(1,300),
        'sector_id_2' => mt_rand(1,300),
        'market_id' => "0100",
        'brand_id' => mt_rand(1,100),
        'original_title' => $faker->name,
        'chinese_title' => '中国名字',
        'original_description' => 'original_'.str_random(10),
        'korean_description' => 'korean_'.str_random(10),
        'english_description' => 'english_'.str_random(10),
        'chinese_description' => 'chinese_'.str_random(10),
        'price' => mt_rand(10000,50000),
        'domestic_delivery_price' => mt_rand(1000,2500),
        'is_free_delivery' => mt_rand(0,1),
        'url' => 'http://www.11st.co.kr/product/SellerProductDetail.tmall?method=getSellerProductDetail&prdNo=333125048&trTypeCd=PW02&trCtgrNo=585021&lCtgrNo=1001452&mCtgrNo=1003081',
        'status_code' => '0300',
        'stock' => mt_rand(1000,2500),
        'safe_stock' => mt_rand(1000,2500),
        'end_date' => date("Y-m-d H:i:s",rand(1262055681,1478304000)),
    ];
});
