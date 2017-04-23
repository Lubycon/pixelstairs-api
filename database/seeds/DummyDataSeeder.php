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

        $users = App\Models\User::all();
        foreach($users as $key => $user){
            $rand = 100; // content quentity
            for( $i=0;$i<$rand;$i++ ){
                if( mt_rand(0,100) > 30 ){
                    App\Models\Like::create([
                        "user_id" => $user->id,
                        'content_id' => $i,
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s"),
                    ]);
                }
            }
        }
    }
}
