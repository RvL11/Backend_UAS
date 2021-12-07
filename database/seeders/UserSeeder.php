<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //GD11
        DB::table('users')->insert([
            'name' => 'Ravelino Sebastian Santoso',
            'email' => '10062@students.uajy.ac.id',
            'password' => '$2b$10$194m6YMX7YS4OTH8coJxO.waJQGaCssQabWOKXWJPmLgNMFr90gv6', //heitayo123.
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
