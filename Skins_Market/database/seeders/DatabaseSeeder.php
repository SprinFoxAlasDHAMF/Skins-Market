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
            ItemSeeder::class,
            ArmaSeeder::class,
        ]);
    }
}
