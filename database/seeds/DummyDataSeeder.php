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
        factory(App\Models\User::class, 100)->create();
        factory(App\Models\Content::class, 100)->create();
        $imageGroup = App\Models\imageGroup::all();
        foreach($imageGroup as $key => $value){
            $rand = 1; // only 1 photo in content
            for( $i=0;$i<$rand;$i++ ){
                App\Models\Image::create([
                    "index" => $i,
                    'url' => "https://unsplash.it/640?image=".mt_rand(1,1000),
                    "image_group_id" => $value['id'],
                ]);
            }
        }
        factory(App\Models\Comment::class, 300)->create();
    }
}
