<?php

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
        'original' => 'original_'.$faker->paragraph,
        'chinese' => 'chinese_'.$faker->paragraph,
        'korean' => 'korean_'.$faker->paragraph,
        'english' => 'english_'.$faker->paragraph,
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
        'section_id_0' => factory(App\Models\SectionMarketInfo::class)->create()->section_id,
        'section_id_1' => mt_rand(0,100) < 7 ? factory(App\Models\SectionMarketInfo::class)->create()->section_id : NULL,
        'section_id_2' => NULL,
    ];
});

$factory->define(App\Models\Section::class, function (Faker\Generator $faker) {
    return [
        'parent_id' => factory(App\Models\Division::class)->create()->id,
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
        'safe_stock' => 15,
        'price' => mt_rand(10000,50000),
        'option_collection_id' => factory(App\Models\OptionCollection::class)->create()->id,
        'image_id' => factory(App\Models\Image::class)->create()->id,
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
        'country_id' => mt_rand(1,200),
    ];
});

$factory->define(App\Models\Seller::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'rate' => mt_rand(1,4).'.'.mt_rand(0,9),
    ];
});

$factory->define(App\Models\Image::class, function (Faker\Generator $faker) {
    return [
        'url' => $faker->imageUrl(),
        'is_mitty_own' => false,
    ];
});

$factory->define(App\Models\ImageGroup::class, function (Faker\Generator $faker) {
    return [
        "model_name" => 'dummy',
    ];
});

$factory->define(App\Models\Product::class, function (Faker\Generator $faker) {
    $statusCode = '030'.mt_rand(0,2);
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
        'weight' => mt_rand(100,1000),
        'original_price' => mt_rand(50000,70000),
        'lower_price' => mt_rand(10000,50000),
        'unit' => "KRW",
        'domestic_delivery_price' => mt_rand(1000,2500),
        'is_free_delivery' => mt_rand(0,1),
        'image_group_id' => factory(App\Models\ImageGroup::class)->create()->id,
        'image_id' => factory(App\Models\Image::class)->create()->id,
        'url' => 'http://www.11st.co.kr/product/SellerProductDetail.tmall?method=getSellerProductDetail&prdNo=333125048&trTypeCd=PW02&trCtgrNo=585021&lCtgrNo=1001452&mCtgrNo=1003081',
        'manufacturer_country_id' => factory(App\Models\Manufacturer::class)->create()->id,
        'status_code' => $statusCode,
        'safe_stock' => mt_rand(15,100),
        'start_date' => $statusCode != '0300' ? date("Y-m-d H:i:s",rand(1262055681,1478304000)) : NULL ,
        'end_date' => date("Y-m-d H:i:s",rand(1262055681,1478304000)),
    ];
});

$factory->define(App\Models\Order::class, function (Faker\Generator $faker) {
    $option = App\Models\Option::find(mt_rand(1,2000));
    return [
        'haitao_order_id' => mt_rand(10000,100000),
        'haitao_user_id' => mt_rand(10000,100000),
        'product_id' => $option['product_id'],
        'sku' => $option['sku'],
        'status_code' => '0313',
        'quantity' => mt_rand(1,3),
        'order_date' => date("Y-m-d H:i:s",rand(1262055681,1478304000)),
    ];
});

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'haitao_user_id' => mt_rand(10000,100000),
        'email' => null,
        'phone' => $faker->phoneNumber,
        'name' => $faker->name,
        'nickname' => $faker->name,
        'password' => bcrypt(env('COMMON_PASSWORD')),
        'status' => mt_rand(0,1) == 0 ? 'inactive' : 'active',
        'grade' => 'normal',
        'position' => null,
        'gender_id' => mt_rand(1,2),
        'birthday' => date("Y-m-d H:i:s",rand(1262055681,1478304000)),
        'city' => $faker->city,
        'address1' => $faker->address,
        'address2' => $faker->streetAddress,
        'post_code' => $faker->postcode,
        'image_id' => factory(App\Models\Image::class)->create()->id,
    ];
});

$factory->define(App\Models\Interest::class, function (Faker\Generator $faker) {
    $division = App\Models\Division::find(mt_rand(1,100));
    $category = App\Models\Category::find($division['parent_id']);
    return [
        'user_id' => mt_rand(1,100),
        'category_id' => $category['id'],
        'division_id' => $division['id'],
    ];
});

$factory->define(App\Models\Review::class, function (Faker\Generator $faker) {
    $product = App\Models\Product::find(mt_rand(1,100));
    return [
        'user_id' => mt_rand(1,100),
        'product_id' => $product['id'],
        'sku' => $product->option->first()['sku'],
        'title' => $faker->streetName,
        'target' => mt_rand(0,1) == 0 ? 'award' : 'buy',
        'image_group_id' => factory(App\Models\ImageGroup::class)->create()->id,
    ];
});

$factory->define(App\Models\ReviewQuestion::class, function (Faker\Generator $faker) {
    $division = App\Models\Division::orderBy(\DB::raw('RAND()'))->first();
    return [
        'division_id' => $division['id'],
        'translate_name_id' => factory(App\Models\TranslateName::class)->create()->id,
        'description' => $faker->paragraph,
    ];
});

$factory->define(App\Models\ReviewAnswer::class, function (Faker\Generator $faker) {
    return [
        'review_id' => factory(App\Models\Review::class)->create()->id,
        'question_id' => mt_rand(1,100),
        'score' => mt_rand(0,5),
        'description' => $faker->paragraph,
    ];
});

$factory->define(App\Models\Award::class, function (Faker\Generator $faker) {
    $product = App\Models\Product::find(mt_rand(1,100));
    return [
        'product_id' => $product['id'],
        'sku' => $product->option->first()['sku'],
        'user_id' => mt_rand(1,100),
        'is_written_review' => mt_rand(0,1),
    ];
});