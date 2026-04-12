<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CategoriaSeeder::class,
            CalidadSeeder::class,
            ExteriorSeeder::class,
            ColorSeeder::class,
            ModoPegatinaSeeder::class,
            PegatinaSeeder::class,
            ItemSeeder::class,
            GuantesSeeder::class,
            ArmaPegatinaSeeder::class,
            LlaveroSeeder::class,
            AgenteSeeder::class,
        ]);
    }
}
