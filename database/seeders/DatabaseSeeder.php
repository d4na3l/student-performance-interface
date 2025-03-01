<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta los seeders de la aplicación.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            BinaryOptionsTableSeeder::class,
        ]);
        $this->call([LevelsTableSeeder::class]);
        $this->call([PeerInfluenceTableSeeder::class]);
        $this->call([SchoolTypesTableSeeder::class]);
        $this->call([GendersTableSeeder::class]);
        $this->call([
            ImportStudentsSeeder::class,
        ]);
    }
}
