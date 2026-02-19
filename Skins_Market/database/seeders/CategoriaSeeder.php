<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        Categoria::create(['nombre' => 'Rifles']);
        Categoria::create(['nombre' => 'Francotiradores']);
        Categoria::create(['nombre' => 'Subfusiles']);
        Categoria::create(['nombre' => 'Guantes']);
        Categoria::create(['nombre' => 'Pegatinas']);
    }
}

