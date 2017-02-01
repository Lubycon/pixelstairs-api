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

        DB::table('categories')->truncate();
        DB::table('divisions')->truncate();
        DB::table('sections')->truncate();
        DB::table('section_groups')->truncate();
        DB::table('section_market_infos')->truncate();
        factory(App\Models\SectionGroup::class, 100)->create();

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
            for( $i=0;$i<mt_rand(0,500);$i++ ) {
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
                ));
            }
        }

        DB::table('orders')->truncate();
        factory(App\Models\Order::class, 100)->create();

        factory(App\Models\User::class, 100)->create();

        DB::table('interests')->truncate();
        factory(App\Models\Interest::class, 100)->create();


        DB::table('review_questions')->truncate();
        factory(App\Models\ReviewQuestion::class, 100)->create();
        
        DB::table('reviews')->truncate();
        DB::table('review_answers')->truncate();
        factory(App\Models\ReviewAnswer::class, 100)->create();

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
