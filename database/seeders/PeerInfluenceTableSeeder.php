<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeerInfluenceTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('peer_influences')->insert([
            ['name' => 'Negative'],
            ['name' => 'Neutral'],
            ['name' => 'Positive'],
        ]);
    }
}