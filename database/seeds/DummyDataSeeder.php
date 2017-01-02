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
        factory(App\Models\User::class, 100)->create();

        DB::table('posts')->truncate();
        factory(App\Models\Post::class, 100)->create();

        DB::table('comments')->truncate();
        factory(App\Models\Comment::class, 1000)->create();

        DB::table('contents')->truncate();
        factory(App\Models\Content::class, 100)->create();

        DB::table('content_category_kernels')->truncate();
        factory(App\Models\ContentCategoryKernel::class, 500)->create();

        DB::table('content_tags')->truncate();
        factory(App\Models\ContentTag::class, 500)->create();
    }
}
