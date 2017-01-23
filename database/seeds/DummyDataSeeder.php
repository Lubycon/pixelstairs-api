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
        //DB::table('users')->truncate(); run this code in admin seeder
        // factory(App\Models\User::class, 100)->create();


        $faker = Faker::create();

        DB::table('categories')->truncate();
        DB::table('divisions')->truncate();
        DB::table('sections')->truncate();
        DB::table('section_groups')->truncate();
        DB::table('section_market_infos')->truncate();
        factory(App\Models\SectionMarketInfo::class, 100)->create();

        for( $i=0; $i<10; $i++ ){
            $int = SectionGroup::orderBy(\DB::raw('RAND()'))->first()['id'];
            $section = Section::create(array(
                'group_id' => $int,
                'translate_name_id' => factory(App\Models\TranslateName::class)->create()->id,
            ));
            SectionMarketInfo::create(array(
                'section_id' => $section['id'],
                'market_id' => '0100',
                'market_category_id' => mt_rand(100000000,110000000),
            ));
        }

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
                        "ST" . $product['section_id'] .
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
