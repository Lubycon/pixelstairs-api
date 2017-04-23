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

$factory->define(App\Models\Image::class, function (Faker\Generator $faker) {
    return [
        "index" => 0,
        'url' => "https://unsplash.it/640?image=".mt_rand(1,1000),
    ];
});