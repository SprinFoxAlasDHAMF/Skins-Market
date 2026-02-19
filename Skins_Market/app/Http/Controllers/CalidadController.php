<?php

namespace App\Http\Controllers;

use App\Models\Calidad;
use Illuminate\Http\Request;

class CalidadController extends Controller
{
    public function index()
    {
        $calidades = Calidad::all();
        return response()->json($calidades);
    }
}

