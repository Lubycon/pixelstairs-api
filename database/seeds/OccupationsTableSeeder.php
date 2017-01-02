<?php

use Illuminate\Database\Seeder;

class OccupationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('occupations')->truncate();
        $occupations = array(
            array('id'=>1,'name'=>'Artist'),
            array('id'=>2,'name'=>'Designer'),
            array('id'=>3,'name'=>'Developer'),
            array('id'=>4,'name'=>'Student'),
            array('id'=>5,'name'=>'Others'),
        );

        DB::table('occupations')->insert($occupations);
    }
}
