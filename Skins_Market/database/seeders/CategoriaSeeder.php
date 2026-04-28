<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        Categoria::create(['nombre' => 'Pistolas']);
        Categoria::create(['nombre' => 'Rifles']);
        Categoria::create(['nombre' => 'Francotiradores']);
        Categoria::create(['nombre' => 'Subfusiles']);
        Categoria::create(['nombre' => 'Guantes']);
        Categoria::create(['nombre' => 'Cuchillos']);
        Categoria::create(['nombre' => 'Pegatinas']);
        Categoria::create(['nombre' => 'Llaveros']);
        Categoria::create(['nombre' => 'Agentes']);
    }
}

