<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CategorySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'name' => 'Other',
            'description' => 'Create Description',
            'is_date' => '0',
            'is_end_date' => '0',
            'status' => '1'
        ]);
    }
}
