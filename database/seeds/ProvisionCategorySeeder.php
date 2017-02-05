<?php

use Illuminate\Database\Seeder;

class ProvisionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->truncate();
        DB::table('divisions')->truncate();
//        DB::table('sections')->truncate();
        $category = [
            [
                "id" => 1,
                "translate_name_id" => 24,
            ],
            [
                "id" => 2,
                "translate_name_id" => 25,
            ],
            [
                "id" => 3,
                "translate_name_id" => 26,
            ],
            [
                "id" => 4,
                "translate_name_id" => 27,
            ],
            [
                "id" => 5,
                "translate_name_id" => 28,
            ],
            [
                "id" => 6,
                "translate_name_id" => 29,
            ],
            [
                "id" => 7,
                "translate_name_id" => 30,
            ],
            [
                "id" => 8,
                "translate_name_id" => 31,
            ],
            [
                "id" => 9,
                "translate_name_id" => 32,
            ],
            [
                "id" => 10,
                "translate_name_id" => 33,
            ],
        ];

        $division = [
            [
                "id" => 1,
                "parent_id" => 1,
                "translate_name_id" => 34,
            ],
            [
                "id" => 2,
                "parent_id" => 1,
                "translate_name_id" => 35,
            ],
            [
                "id" => 3,
                "parent_id" => 1,
                "translate_name_id" => 36,
            ],
            [
                "id" => 4,
                "parent_id" => 1,
                "translate_name_id" => 37,
            ],
            [
                "id" => 5,
                "parent_id" => 1,
                "translate_name_id" => 38,
            ],
            [
                "id" => 6,
                "parent_id" => 1,
                "translate_name_id" => 39,
            ],
            [
                "id" => 7,
                "parent_id" => 2,
                "translate_name_id" => 40,
            ],
            [
                "id" => 8,
                "parent_id" => 2,
                "translate_name_id" => 41,
            ],
            [
                "id" => 9,
                "parent_id" => 2,
                "translate_name_id" => 42,
            ],
            [
                "id" => 10,
                "parent_id" => 2,
                "translate_name_id" => 43,
            ],
            [
                "id" => 11,
                "parent_id" => 2,
                "translate_name_id" => 44,
            ],
            [
                "id" => 12,
                "parent_id" => 2,
                "translate_name_id" => 45,
            ],
            [
                "id" => 13,
                "parent_id" => 2,
                "translate_name_id" => 46,
            ],
            [
                "id" => 14,
                "parent_id" => 2,
                "translate_name_id" => 47,
            ],
            [
                "id" => 15,
                "parent_id" => 2,
                "translate_name_id" => 48,
            ],
            [
                "id" => 16,
                "parent_id" => 2,
                "translate_name_id" => 49,
            ],
            [
                "id" => 17,
                "parent_id" => 3,
                "translate_name_id" => 50,
            ],
            [
                "id" => 18,
                "parent_id" => 3,
                "translate_name_id" => 51,
            ],
            [
                "id" => 19,
                "parent_id" => 3,
                "translate_name_id" => 52,
            ],
            [
                "id" => 20,
                "parent_id" => 3,
                "translate_name_id" => 53,
            ],
            [
                "id" => 21,
                "parent_id" => 3,
                "translate_name_id" => 54,
            ],
            [
                "id" => 22,
                "parent_id" => 3,
                "translate_name_id" => 55,
            ],
            [
                "id" => 23,
                "parent_id" => 3,
                "translate_name_id" => 56,
            ],
            [
                "id" => 24,
                "parent_id" => 3,
                "translate_name_id" => 57,
            ],
            [
                "id" => 25,
                "parent_id" => 3,
                "translate_name_id" => 58,
            ],
            [
                "id" => 26,
                "parent_id" => 3,
                "translate_name_id" => 59,
            ],
            [
                "id" => 27,
                "parent_id" => 3,
                "translate_name_id" => 60,
            ],
            [
                "id" => 28,
                "parent_id" => 3,
                "translate_name_id" => 61,
            ],
            [
                "id" => 29,
                "parent_id" => 3,
                "translate_name_id" => 62,
            ],
            [
                "id" => 30,
                "parent_id" => 4,
                "translate_name_id" => 63,
            ],
            [
                "id" => 31,
                "parent_id" => 4,
                "translate_name_id" => 64,
            ],
            [
                "id" => 32,
                "parent_id" => 4,
                "translate_name_id" => 65,
            ],
            [
                "id" => 33,
                "parent_id" => 4,
                "translate_name_id" => 66,
            ],
            [
                "id" => 34,
                "parent_id" => 4,
                "translate_name_id" => 67,
            ],
            [
                "id" => 35,
                "parent_id" => 4,
                "translate_name_id" => 68,
            ],
            [
                "id" => 36,
                "parent_id" => 4,
                "translate_name_id" => 69,
            ],
            [
                "id" => 37,
                "parent_id" => 4,
                "translate_name_id" => 70,
            ],
            [
                "id" => 38,
                "parent_id" => 4,
                "translate_name_id" => 71,
            ],
            [
                "id" => 39,
                "parent_id" => 4,
                "translate_name_id" => 72,
            ],
            [
                "id" => 40,
                "parent_id" => 4,
                "translate_name_id" => 73,
            ],
            [
                "id" => 41,
                "parent_id" => 4,
                "translate_name_id" => 74,
            ],
            [
                "id" => 42,
                "parent_id" => 4,
                "translate_name_id" => 75,
            ],
            [
                "id" => 43,
                "parent_id" => 4,
                "translate_name_id" => 76,
            ],
            [
                "id" => 44,
                "parent_id" => 4,
                "translate_name_id" => 77,
            ],
            [
                "id" => 45,
                "parent_id" => 4,
                "translate_name_id" => 78,
            ],
            [
                "id" => 46,
                "parent_id" => 4,
                "translate_name_id" => 79,
            ],
            [
                "id" => 47,
                "parent_id" => 4,
                "translate_name_id" => 80,
            ],
        ];


        DB::table('categories')->insert($category);
        DB::table('divisions')->insert($division);
    }
}
