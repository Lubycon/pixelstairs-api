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


$factory->define(App\Models\TranslateName::class, function (Faker\Generator $faker) {
    return [
        'original' => 'original_'.$faker->streetName,
        'chinese' => 'chinese_'.$faker->streetName,
        'korean' => 'korean_'.$faker->streetName,
        'english' => 'english_'.$faker->streetName,
    ];
});
$factory->define(App\Models\TranslateDescription::class, function (Faker\Generator $faker) {
    return [
        'original' => 'original_'.$faker->streetName,
        'chinese' => 'chinese_'.$faker->streetName,
        'korean' => 'korean_'.$faker->streetName,
        'english' => 'english_'.$faker->streetName,
    ];
});


$factory->define(App\Models\Category::class, function (Faker\Generator $faker) {
    return [
        'translate_name_id' => factory(App\Models\TranslateName::class)->create()->id,
    ];
});

$factory->define(App\Models\Division::class, function (Faker\Generator $faker) {
    return [
        'parent_id' => factory(App\Models\Category::class)->create()->id,
        'translate_name_id' => factory(App\Models\TranslateName::class)->create()->id,
    ];
});

$factory->define(App\Models\SectionGroup::class, function (Faker\Generator $faker) {
    return [
        'parent_id' => factory(App\Models\Division::class)->create()->id,
    ];
});

$factory->define(App\Models\Section::class, function (Faker\Generator $faker) {
    return [
        'group_id' => factory(App\Models\SectionGroup::class)->create()->id,
        'translate_name_id' => factory(App\Models\TranslateName::class)->create()->id,
    ];
});

$factory->define(App\Models\SectionMarketInfo::class, function (Faker\Generator $faker) {
    return [
        'section_id' => factory(App\Models\Section::class)->create()->id,
        'market_id' => '0100',
        'market_category_id' => mt_rand(100000000,110000000),
    ];
});


$factory->define(App\Models\Option::class, function (Faker\Generator $faker) {
    return [
        'product_id' => mt_rand(1,100),
        'sku' =>
            "MK0100".
            "CT".mt_rand(1,30).
            "DV".mt_rand(1,100).
            "ST".mt_rand(1,300).
            "PD".mt_rand(100000000,110000000).
            "ID".mt_rand(1,100),
        'translate_name_id' => factory(App\Models\TranslateName::class)->create()->id,
        'stock' => mt_rand(100,500),
        'safe_stock' => mt_rand(10,50),
        'price' => mt_rand(10000,50000),
        'option_collection_id' => factory(App\Models\OptionCollection::class)->create()->id,
    ];
});

$factory->define(App\Models\OptionCollection::class, function (Faker\Generator $faker) {
    return [
        'option_key_id_0' => factory(App\Models\OptionKey::class)->create()->id,
        'option_key_id_1' => factory(App\Models\OptionKey::class)->create()->id,
        'option_key_id_2' => factory(App\Models\OptionKey::class)->create()->id,
//        'option_key_id_3' => factory(App\Models\OptionKey::class)->create()->id,
    ];
});

$factory->define(App\Models\OptionKey::class, function (Faker\Generator $faker) {
    return [
        'translate_name_id' => factory(App\Models\TranslateName::class)->create()->id,
    ];
});


$factory->define(App\Models\Brand::class, function (Faker\Generator $faker) {
    return [
        'translate_name_id' => factory(App\Models\TranslateName::class)->create()->id,
    ];
});

$factory->define(App\Models\Manufacturer::class, function (Faker\Generator $faker) {
    return [
        'translate_name_id' => factory(App\Models\TranslateName::class)->create()->id,
    ];
});

$factory->define(App\Models\Seller::class, function (Faker\Generator $faker) {
    return [
        'translate_name_id' => factory(App\Models\TranslateName::class)->create()->id,
        'rate' => mt_rand(1,9).'.'.mt_rand(0,9),
    ];
});


$factory->define(App\Models\Product::class, function (Faker\Generator $faker) {
    $statusCode = '030'.mt_rand(0,3);
    $sectionGroup = App\Models\SectionGroup::find(mt_rand(1,100));
    $division = App\Models\Division::find($sectionGroup['parent_id']);
    $category = App\Models\Category::find($division['parent_id']);

    return [
        'market_product_id' => mt_rand(100000000,110000000),
        'haitao_product_id' => $statusCode != '0300' ? mt_rand(100000000,110000000) : NULL,
        'category_id' => $category['id'],
        'division_id' => $division['id'],
        'section_group_id' => $sectionGroup['id'],
        'market_id' => "0100",
        'brand_id' => factory(App\Models\Brand::class)->create()->id,
        'seller_id' => factory(App\Models\Seller::class)->create()->id,
        'gender_id' => mt_rand(0,2),
        'translate_name_id' => factory(App\Models\TranslateName::class)->create()->id,
        'translate_description_id' => factory(App\Models\TranslateDescription::class)->create()->id,
        'weight' => mt_rand(1,100),
        'original_price' => mt_rand(50000,70000),
        'lower_price' => mt_rand(10000,50000),
        'unit' => "KRW",
        'domestic_delivery_price' => mt_rand(1000,2500),
        'is_free_delivery' => mt_rand(0,1),
        'thumbnail_url' => $faker->imageUrl,
        'url' => 'http://www.11st.co.kr/product/SellerProductDetail.tmall?method=getSellerProductDetail&prdNo=333125048&trTypeCd=PW02&trCtgrNo=585021&lCtgrNo=1001452&mCtgrNo=1003081',
        'manufacturer_id' => factory(App\Models\Manufacturer::class)->create()->id,
        'status_code' => $statusCode,
        'stock' => mt_rand(1000,2500),
        'safe_stock' => mt_rand(1000,2500),
        'start_date' => $statusCode != '0300' ? date("Y-m-d H:i:s",rand(1262055681,1478304000)) : NULL ,
        'end_date' => date("Y-m-d H:i:s",rand(1262055681,1478304000)),
    ];
});
