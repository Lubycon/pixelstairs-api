<?php

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Section;
use App\Models\SectionMarketInfo;
use App\Models\SectionGroup;
use App\Models\Option;
use App\Models\OptionCollection;
use App\Models\TranslateName;
use Faker\Factory as Faker;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

//        in image table seeder truncate
//        DB::table('images')->truncate();
        DB::table('image_groups')->truncate();

//        DB::table('categories')->truncate();
//        DB::table('divisions')->truncate();
        DB::table('sections')->truncate();
        DB::table('section_groups')->truncate();
        DB::table('section_market_infos')->truncate();
        factory(App\Models\SectionGroup::class, 100)->create();

        DB::table('free_gifts')->truncate();
        DB::table('free_gift_groups')->truncate();
        DB::table('brands')->truncate();
        DB::table('sellers')->truncate();
        DB::table('products')->truncate();
        factory(App\Models\Product::class, 100)->create();


        DB::table('options')->truncate();
        DB::table('option_collections')->truncate();
        DB::table('option_keys')->truncate();
        factory(App\Models\OptionCollection::class, 100)->create();

        $optionCollection = OptionCollection::all();
        foreach( $optionCollection as $key => $value ){
            $collectionNumber = $value['id'];
            $product = Product::find($value['id']);
            $optionName = $this->optionNameGenerate($faker);
            for( $i=0;$i<mt_rand(1,500);$i++ ) {
                Option::create(array(
                    'product_id' => $product['id'],
                    'sku' =>
                        "MK0100" .
                        "CT" . $product['category_id'] .
                        "DV" . $product['division_id'] .
                        "ST" . $product['section_group_id'] .
                        "PD" . $product['id'] .
                        "ID" . $i,
                    'translate_name_id' => TranslateName::create($this->optionArrayGenerate($optionName,$i))['id'],
                    'stock' => mt_rand(100, 500),
                    'safe_stock' => mt_rand(10, 50),
                    'price' => mt_rand(10000, 50000),
                    'option_collection_id' => $collectionNumber,
                    'image_id' => factory(App\Models\Image::class)->create()->id,
                ));
            }
        }

        DB::table('orders')->truncate();
        factory(App\Models\Order::class, 100)->create();

        factory(App\Models\User::class, 100)->create();

        DB::table('interests')->truncate();
        factory(App\Models\Interest::class, 100)->create();


        DB::table('review_questions')->truncate();
        DB::table('review_question_keys')->truncate();
        factory(App\Models\ReviewQuestion::class, 800)->create();

        DB::table('reviews')->truncate();
        DB::table('review_answers')->truncate();
        factory(App\Models\ReviewAnswer::class, 100)->create();


        $products = App\Models\Product::all();
        foreach($products as $key => $value){
            $giftGroup = App\Models\FreeGiftGroup::create([
                "product_id" => $value['id'],
                "stock_per_each" => mt_rand(1,3),
                "first_deploy_count" => mt_rand(5,10),
            ]);
            $value->free_gift_group_id = $giftGroup['id'];
            $value->save();
            $rand = mt_rand(6,10);

            for( $i=0;$i<$rand;$i++ ){
                App\Models\FreeGift::create([
                    "group_id" => $giftGroup['id'],
                    "option_id" => $value->option()->orderBy(\DB::raw('RAND()'))->first()['id'],
                    "stock" => $giftGroup['stock_per_each'] * $rand,
                ]);
            }
        }

        DB::table('give_products')->truncate();
        factory(App\Models\GiveProduct::class, 100)->create();

        factory(App\Models\Image::class, 300)->create();
        $imageGroup = App\Models\imageGroup::all();
        foreach($imageGroup as $key => $value){
            $rand = mt_rand(2,5);
            for( $i=0;$i<$rand;$i++ ){
                App\Models\Image::create([
                    "index" => $i,
                    "url" => $faker->imageUrl,
                    "image_group_id" => $value['id'],
                ]);
            }
        }

        DB::table('awards')->truncate();
        factory(App\Models\Award::class, 100)->create();


    }

    public function optionNameGenerate($faker){
        return array(
            'option0' => $faker->colorName,
            'option1' => $faker->monthName,
            'option2' => $faker->streetName,
        );
    }

    public function optionArrayGenerate($optionName,$i){
        $option = array(
            'original' =>
                'origin_'.$optionName['option0'].','.
                'origin_'.$optionName['option1'].','.
                'origin_'.$optionName['option2'].$i,
            'chinese' =>
                '选项_'.$optionName['option0'].','.
                '选项_'.$optionName['option1'].','.
                '选项_'.$optionName['option2'].$i,
            'korean' =>
                '옵션_'.$optionName['option0'].','.
                '옵션_'.$optionName['option1'].','.
                '옵션_'.$optionName['option2'].$i,
            'english' =>
                'option_'.$optionName['option0'].','.
                'option_'.$optionName['option1'].','.
                'option_'.$optionName['option2'].$i,
        );
        return $option;
    }
}
