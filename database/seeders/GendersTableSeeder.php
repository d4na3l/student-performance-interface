<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GendersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('genders')->insert([
            ['name' => 'Male'],
            ['name' => 'Female'],
        ]);
    }
}