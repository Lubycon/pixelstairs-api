<?php

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'email'=> $faker->email,
        'nickname' => $faker->name,
        'password' => bcrypt(env('COMMON_PASSWORD')),
        'status' => 'active',
        'grade' => 'general',
        'gender' => mt_rand(0,1) == 0 ? 'male' : 'female',
        'image_id' => factory(App\Models\Image::class)->create()->id,
        'newsletters_accepted' => mt_rand(0,1),
        'terms_of_service_accepted' => true,
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s"),
    ];
});

//description, hash_tags, id, image_group_id, licence_code, like_count, title, updated_at, user_id, view_count


$factory->define(App\Models\Content::class, function (Faker\Generator $faker) {
    $hash_tags = $faker->words(mt_rand(0,3));
    return [
        'user_id' => mt_rand(2,100),
        'title' => $faker->sentence,
        'description'=> $faker->paragraph,
        'hash_tags' => count($hash_tags) > 0 ? json_encode($hash_tags) : null,
        'image_group_id' => factory(App\Models\ImageGroup::class)->create()->id,
        'licence_code' => '1234',
        'view_count' => mt_rand(300,1000),
        'like_count' => mt_rand(500,10000),
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s"),
    ];
});

$factory->define(App\Models\Comment::class, function (Faker\Generator $faker) {
    return [
        'user_id' => mt_rand(2,100),
        'content_id' => mt_rand(1,100),
        'description'=> $faker->paragraph,
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s"),
    ];
});

$factory->define(App\Models\ImageGroup::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Image::class, function (Faker\Generator $faker) {
    return [
        "index" => 0,
        'url' => "https://unsplash.it/640?image=".mt_rand(1,1000),
    ];
});