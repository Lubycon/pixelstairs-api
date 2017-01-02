<?php

use Illuminate\Database\Seeder;

class ContentCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('content_categories')->truncate();
        $ContentCategories = array(
            array('id'=>1,'name'=>'3D scans'),
            array('id'=>2,'name'=>'Architecture'),
            array('id'=>3,'name'=>'Art'),
            array('id'=>4,'name'=>'Characters'),
            array('id'=>5,'name'=>'DIY'),
            array('id'=>6,'name'=>'Electronic'),
            array('id'=>7,'name'=>'Fashion'),
            array('id'=>8,'name'=>'Furniture'),
            array('id'=>9,'name'=>'Game'),
            array('id'=>10,'name'=>'Household'),
            array('id'=>11,'name'=>'Industrial'),
            array('id'=>12,'name'=>'Interior'),
            array('id'=>13,'name'=>'Jewellery'),
            array('id'=>14,'name'=>'Kitchen'),
            array('id'=>15,'name'=>'Medical'),
            array('id'=>16,'name'=>'Models'),
            array('id'=>17,'name'=>'Object'),
            array('id'=>18,'name'=>'Pets'),
            array('id'=>19,'name'=>'Scenery'),
            array('id'=>20,'name'=>'Sculpture'),
            array('id'=>21,'name'=>'Tools'),
            array('id'=>22,'name'=>'Toys'),
            array('id'=>23,'name'=>'Vehicles'),
            array('id'=>24,'name'=>'Various'),
        );

        DB::table('content_categories')->insert($ContentCategories);
    }
}
