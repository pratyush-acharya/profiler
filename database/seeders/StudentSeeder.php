<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('students')->insert([
            'user_id' => '2',
            'roll' => '1',
            'image' => 'image/url',
            'application_form' => 'application/form',
            'batch_id' => '1',
            // 'graduation_date'=>'',
        ]);

        DB::table('students')->insert([
            'user_id' => '3',
            'roll' => '2',
            'image' => 'image/url',
            'application_form' => 'application/form',
            'batch_id' => '2',
            // 'graduation_date'=>'',
        ]);

        DB::table('students')->insert([
            'user_id' => '4',
            'roll' => '3',
            'image' => 'image/url',
            'application_form' => 'application/form',
            'batch_id' => '3',
            // 'graduation_date'=>'',
        ]);

        DB::table('students')->insert([
            'user_id' => '5',
            'roll' => '4',
            'image' => 'image/url',
            'application_form' => 'application/form',
            'batch_id' => '3',
            // 'graduation_date'=>'',
        ]);

         DB::table('students')->insert([
            'user_id' => '6',
            'roll' => '5',
            'image' => 'image/url',
            'application_form' => 'application/form',
            'batch_id' => '3',
            // 'graduation_date'=>'',
        ]);

        DB::table('students')->insert([
            'user_id' => '7',
            'roll' => '6',
            'image' => 'image/url',
            'application_form' => 'application/form',
            'batch_id' => '3',
            // 'graduation_date'=>'',
        ]);

        DB::table('students')->insert([
            'user_id' => '8',
            'roll' => '7',
            'image' => 'image/url',
            'application_form' => 'application/form',
            'batch_id' => '3',
            // 'graduation_date'=>'',
        ]);

        DB::table('students')->insert([
            'user_id' => '9',
            'roll' => '3',
            'image' => 'image/url',
            'application_form' => 'application/form',
            'batch_id' => '2',
            // 'graduation_date'=>'',
        ]);

        DB::table('students')->insert([
            'user_id' => '10',
            'roll' => '4',
            'image' => 'image/url',
            'application_form' => 'application/form',
            'batch_id' => '2',
            // 'graduation_date'=>'',
        ]);
        DB::table('students')->insert([
            'user_id' => '11',
            'roll' => '5',
            'image' => 'image/url',
            'application_form' => 'application/form',
            'batch_id' => '2',
            // 'graduation_date'=>'',
        ]);

        DB::table('students')->insert([
            'user_id' => '12',
            'roll' => '23',
            'image' => 'image/url',
            'application_form' => 'application/form',
            'batch_id' => '2',
            // 'graduation_date'=>'',
        ]);

        DB::table('students')->insert([
            'user_id' => '13',
            'roll' => '8',
            'image' => 'image/url',
            'application_form' => 'application/form',
            'batch_id' => '3',
            // 'graduation_date'=>'',
        ]);
        
    }
}
