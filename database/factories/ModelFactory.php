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

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'nickname' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'occupation_id' => mt_rand(1,5),
        'country_id' => mt_rand(1,240),
        'profile_img' => "http://lorempixel.com/640/640/",
        'status' => 'active',
        'newsletter' => mt_rand(0,1),
    ];
});

$factory->define(App\Models\Post::class, function (Faker\Generator $faker) {
    return [
        'board_id' => 11,
        'user_id' => mt_rand(1,100),
        'title' => $faker->name,
        'content' => str_random(10),
        'directory' => 'path',
        "comment_count" => mt_rand(0,100),
        "like_count" => mt_rand(0,100),
        "view_count" => mt_rand(0,100),
        "created_at" => date("Y-m-d H:i:s",rand(1262055681,1478304000)),
    ];
});

$factory->define(App\Models\Content::class, function (Faker\Generator $faker) {
    return [
        'board_id' => 1,
        'user_id' => mt_rand(1,100),
        'license_id' => mt_rand(1,6),
        'title' => $faker->name,
        'description' => str_random(10),
        'content' => str_random(10),
        'directory' => public_path().'/datas/1',
        'is_download' => mt_rand(0,1) ? true : false,
        "download_count" => mt_rand(0,100),
        "bookmark_count" => mt_rand(0,100),
        "comment_count" => mt_rand(0,100),
        "like_count" => mt_rand(0,100),
        "view_count" => mt_rand(0,100),
        "created_at" => date("Y-m-d H:i:s",rand(1262055681,1478304000)),
    ];
});

$factory->define(App\Models\Comment::class, function (Faker\Generator $faker) {
    return [
        'give_user_id' => mt_rand(1,100),
        'take_user_id' => mt_rand(1,100),
        'board_id' => mt_rand(0,1) ? 1 : 11,
        'post_id' => mt_rand(0,100),
        "content" => str_random(10),
        "created_at" => date("Y-m-d H:i:s",rand(1262055681,1478304000)),
    ];
});

$factory->define(App\Models\ContentCategoryKernel::class, function (Faker\Generator $faker) {
    return [
        'post_id' => mt_rand(1,100),
        "category_id" => mt_rand(1,24),
    ];
});

$factory->define(App\Models\ContentTag::class, function (Faker\Generator $faker) {
    return [
        'post_id' => mt_rand(1,100),
        "name" => $faker->name,
    ];
});
