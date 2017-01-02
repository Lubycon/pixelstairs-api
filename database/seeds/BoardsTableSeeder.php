<?php

use Illuminate\Database\Seeder;

class BoardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('boards')->truncate();
        $boards = array(
            array('id'=>1,'name'=>'3D','group'=>'content'),
            array('id'=>2,'name'=>'Artwork','group'=>'content'),
            array('id'=>3,'name'=>'Vector','group'=>'content'),
            array('id'=>11,'name'=>'Forum','group'=>'post'),
            array('id'=>12,'name'=>'Tutorial','group'=>'post'),
            array('id'=>13,'name'=>'Q&A','group'=>'post')
        );

        DB::table('boards')->insert($boards);
    }
}
