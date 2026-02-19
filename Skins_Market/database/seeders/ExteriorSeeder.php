<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exterior;

class ExteriorSeeder extends Seeder
{
    public function run()
    {
        Exterior::create(['nombre' => 'Factory New']);
        Exterior::create(['nombre' => 'Minimal Wear']);
        Exterior::create(['nombre' => 'Field-Tested']);
        Exterior::create(['nombre' => 'Well-Worn']);
        Exterior::create(['nombre' => 'Battle-Scarred']);
    }
}

