<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PegatinaController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('pegatina')
            ->join('modo_pegatina', 'pegatina.modo_pegatina_id', '=', 'modo_pegatina.id')
            ->select(
                'pegatina.id',
                'pegatina.nombre',
                'pegatina.precio',
                'pegatina.imagen',
                'pegatina.modo_pegatina_id',
                'modo_pegatina.nombre as modo'
            );

        if ($request->filled('modo_pegatina_id')) {
            $query->where('pegatina.modo_pegatina_id', $request->modo_pegatina_id);
        }

        return response()->json($query->get());
    }

    public function modos()
    {
        return DB::table('modo_pegatina')->get();
    }
}