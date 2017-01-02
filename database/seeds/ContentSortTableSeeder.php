<?php

use Illuminate\Database\Seeder;

class ContentSortTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('content_sorts')->truncate();
        $ContentSorts = array(
            array('id'=>1,'name'=>'Recent'),
            array('id'=>2,'name'=>'Most View'),
            array('id'=>3,'name'=>'Most Comment'),
            array('id'=>4,'name'=>'Most Download'),
        );

        DB::table('content_sorts')->insert($ContentSorts);
    }
}
