<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BinaryOptionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('binary_options')->insert([
            ['name' => 'Yes'],
            ['name' => 'No'],
        ]);
    }
}