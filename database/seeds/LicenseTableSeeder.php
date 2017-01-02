<?php

use Illuminate\Database\Seeder;

class LicenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('licenses')->truncate();
        $licenses = array(
            array("id" => 1 ,"code"=> "1000" , "url"=> "http://creativecommons.org/licenses/by/4.0" , "icon" => "http://lorempixel.com/100/100/", "description" => "Copy and redistribute the material in any medium or format, remix, transform, and build upon the material for any purpose, even commercially."),
            array("id" => 2 ,"code"=> "1100" , "url"=> "http://creativecommons.org/licenses/by-nc/4.0" , "icon" => "http://lorempixel.com/100/100/", "description" => "Copy and redistribute the material in any medium or format remix, transform, and build upon the material"),
            array("id" => 3 ,"code"=> "1110" , "url"=> "http://creativecommons.org/licenses/by-nc-nd/4.0" , "icon" => "http://lorempixel.com/100/100/", "description" => " Copy and redistribute the material in any medium or formatThe licensor cannot revoke these freedoms as long as you follow the license terms."),
            array("id" => 4 ,"code"=> "1101" , "url"=> "http://creativecommons.org/licenses/by-nc-sa/4.0" , "icon" => "http://lorempixel.com/100/100/", "description" => "Copy and redistribute the material in any medium or format remix, transform, and build upon the material."),
            array("id" => 5 ,"code"=> "1010" , "url"=> "http://creativecommons.org/licenses/by-nd/4.0" , "icon" => "http://lorempixel.com/100/100/", "description" => "Copy and redistribute the material in any medium or format for any purpose, even commercially. The licensor cannot revoke these freedoms as long as you follow the license terms."),
            array("id" => 6 ,"code"=> "0" , "url"=> "#" , "icon" => "http://lorempixel.com/100/100/", "description" => "Without CommonCreators. I don't care what you do."),
        );

        DB::table('licenses')->insert($licenses);
    }
}
