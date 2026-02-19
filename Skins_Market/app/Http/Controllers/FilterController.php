<?php

namespace App\Http\Controllers;

use App\Models\Calidad;
use App\Models\Exterior;
use App\Models\Categoria;

class FilterController extends Controller
{
    public function getFilters()
    {
        $calidades = Calidad::all();
        $exteriores = Exterior::all();
        $categorias = Categoria::all();

        return response()->json([
            'calidades' => $calidades,
            'exteriores' => $exteriores,
            'categorias' => $categorias,
        ]);
    }
}

