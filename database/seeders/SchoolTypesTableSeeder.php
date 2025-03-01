<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolTypesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('school_types')->insert([
            ['name' => 'Private'],
            ['name' => 'Public'],
        ]);
    }
}