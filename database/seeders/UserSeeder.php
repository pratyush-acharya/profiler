<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'DMT',
            'email' => 'dmt@deerwalk.edu.np',
            'role' => 'admin',
            'password' => Hash::make('deerwalk', [12])
        ]);

        DB::table('users')->insert([
            'name' => 'Deena Sitikhu',
            'email' => 'deena.sitikhu@deerwalk.edu.np',
            'role' => 'student',
            'password' => Hash::make('deerwalk', [12])
        ]);
        DB::table('users')->insert([
            'name' => 'Pradeepti Aryal',
            'email' => 'pradeepti.aryal@deerwalk.edu.np',
            'role' => 'student',
            'password' => Hash::make('deerwalk', [12])
        ]);

        DB::table('users')->insert([
            'name' => 'Aabishkar Pandey',
            'email' => 'aabiskar.pandey@deerwalk.edu.np',
            'role' => 'student',
            'password' => Hash::make('deerwalk', [12])
        ]);
        DB::table('users')->insert([
            'name' => 'Aahishma Khanal',
            'email' => 'aahishma.khanal@deerwalk.edu.np',
            'role' => 'student',
            'password' => Hash::make('deerwalk', [12])
        ]);

        DB::table('users')->insert([
            'name' => 'Aakash Bhandari',
            'email' => 'aakash.bhandari@deerwalk.edu.np',
            'role' => 'student',
            'password' => Hash::make('deerwalk', [12])
        ]);
        DB::table('users')->insert([
            'name' => 'Aashish Sapkota',
            'email' => 'aashish.sapkota@deerwalk.edu.np',
            'role' => 'student',
            'password' => Hash::make('deerwalk', [12])
        ]);
         DB::table('users')->insert([
            'name' => 'Aashish Tamang',
            'email' => 'aashish.tamang@deerwalk.edu.np',
            'role' => 'student',
            'password' => Hash::make('deerwalk', [12])
        ]);


        DB::table('users')->insert([
            'name' => 'Ashim Garbuja',
            'email' => 'ashim.garbuja@deerwalk.edu.np',
            'role' => 'student',
            'password' => Hash::make('deerwalk', [12])
        ]);
        DB::table('users')->insert([
            'name' => 'himal neupane',
            'email' => 'himal.neupane@deerwalk.edu.np',
            'role' => 'student',
            'password' => Hash::make('deerwalk', [12])
        ]);
         DB::table('users')->insert([
            'name' => 'nabin katwal',
            'email' => 'nabin.katwal@deerwalk.edu.np',
            'role' => 'student',
            'password' => Hash::make('deerwalk', [12])
        ]);
        DB::table('users')->insert([
            'name' => 'nishan pokharel',
            'email' => 'nishan.pokharel@deerwalk.edu.np',
            'role' => 'student',
            'password' => Hash::make('deerwalk', [12])
        ]);

        DB::table('users')->insert([
            'name' => 'jessica shrestha',
            'email' => 'jessica.shrestha@deerwalk.edu.np',
            'role' => 'student',
            'password' => Hash::make('deerwalk', [12])
        ]);          

    }
}
