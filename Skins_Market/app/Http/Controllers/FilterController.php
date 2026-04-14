<?php

namespace App\Http\Controllers;

use App\Models\Calidad;
use App\Models\Exterior;
use App\Models\Categoria;
use App\Models\Color;

class FilterController extends Controller
{
    public function getFilters()
    {
        $calidades = Calidad::all();
        $exteriores = Exterior::all();
        $categorias = Categoria::all();
        $colores = Color::all();

        return response()->json([
            'calidades' => $calidades,
            'exteriores' => $exteriores,
            'categorias' => $categorias,
            'colores' => $colores,
        ]);
    }
}

