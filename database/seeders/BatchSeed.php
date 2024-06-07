<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BatchSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('batches')->insert([
            'year' => '2022',
            'stream' => 'BCA',
            'start_date' => '2020-01-01',
            'end_date' => '2022-12-31',
            'present_sem' => '6',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('batches')->insert([
            'year' => '2022',
            'stream' => 'Bsc. CSIT',
            'start_date' => '2020-01-01',
            'end_date' => '2022-12-31',
            'present_sem' => '6',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

         DB::table('batches')->insert([
            'year' => '2021',
            'stream' => 'Bsc. CSIT',
            'start_date' => '2019-01-01',
            'end_date' => '2022-12-31',
            'present_sem' => '8',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
