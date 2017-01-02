<?php

use Illuminate\Database\Seeder;

class PostSortTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('post_sorts')->truncate();
        $PostSorts = array(
            array('id'=>1,'name'=>'Recent'),
            array('id'=>2,'name'=>'Most View'),
            array('id'=>3,'name'=>'Most Comment'),
        );

        DB::table('post_sorts')->insert($PostSorts);
    }
}
