<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Arma;
use App\Models\Calidad;
use App\Models\Categoria;
use App\Models\Exterior;

class SkinController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['calidad', 'armas.exterior', 'armas.categoria']);

        if ($request->filled('calidad_id')) {
            $query->where('calidad_id', $request->calidad_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('precio_min')) {
            $query->where('precio', '>=', $request->precio_min);
        }

        if ($request->filled('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }

        if ($request->filled('color')) {
            $query->where('color', 'like', '%' . $request->color . '%');
        }

        if ($request->filled('categoria_id')) {
            $query->whereHas('armas', fn($q) =>
                $q->where('categoria_id', $request->categoria_id)
            );
        }

        if ($request->filled('exterior_id')) {
            $query->whereHas('armas', fn($q) =>
                $q->where('exterior_id', $request->exterior_id)
            );
        }

        $items = $query->get()->map(function ($item) {
            $arma = $item->armas->first();

            return [
                'id' => $item->id,
                'nombre' => $item->nombre,
                'color' => $item->color,
                'precio' => $item->precio,
                'foto' => $item->foto,
                'calidad' => $item->calidad->nombre ?? null,
                'categoria' => $arma?->categoria->nombre,
                'exterior' => $arma?->exterior->nombre,
            ];
        });

        return response()->json($items);
    }

    public function show($id)
    {
        $item = Item::with(['calidad', 'armas.exterior', 'armas.categoria', 'armas.pegatinas.modoPegatina'])
                    ->findOrFail($id);

        $exteriores = Exterior::all(); // todos los exteriores posibles

        return response()->json([
            'item' => [
                'id' => $item->id,
                'nombre' => $item->nombre,
                'precio' => $item->precio,
                'foto' => $item->foto,
                'calidad' => $item->calidad->nombre,
                'armas' => $item->armas->map(function($arma) {
                    return [
                        'categoria' => $arma->categoria,
                        'exterior' => $arma->exterior,
                        'pegatinas' => $arma->pegatinas->map(function($p) {
                            return [
                                'modoPegatina' => $p->modoPegatina
                            ];
                        }),
                    ];
                }),
            ],
            'exteriores' => $exteriores,
        ]);
    }


    public function filtrarExterior($item_id, $exterior_id)
    {
        $item = Item::findOrFail($item_id);

        $armas = Arma::with(['item', 'categoria', 'pegatinas.modoPegatina', 'exterior'])
                    ->whereHas('item', function($q) use ($item) {
                        $q->where('nombre', $item->nombre);
                    })
                    ->where('exterior_id', $exterior_id)
                    ->get();

        return response()->json([
            'armas' => $armas
        ]);
    }

}
