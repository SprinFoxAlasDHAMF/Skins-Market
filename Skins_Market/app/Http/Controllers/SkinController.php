<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Arma;
use App\Models\Calidad;
use App\Models\Categoria;
use App\Models\Exterior;
use App\Models\Color;

class SkinController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['calidad', 'armas.exterior', 'armas.categoria', 'color']);

        if ($request->filled('nombre')) {
            $query->where('nombre', 'LIKE', '%' . $request->nombre . '%');
        }
        
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
            $query->where('color_id', $request->color);
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
                'color' => $item->color?->nombre,
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
        $item = Item::with([
            'calidad',
            'armas.exterior',
            'armas.categoria',
            'armas.pegatinas.modoPegatina'
        ])->findOrFail($id);
    
        // Todos los exteriores (para selector en frontend)
        $exteriores = Exterior::all()->map(function ($ext) {
            return [
                'id' => $ext->id,
                'nombre' => $ext->nombre
            ];
        });
    
        return response()->json([
            'item' => [
                'id' => $item->id,
                'nombre' => $item->nombre,
                'precio' => $item->precio,
                'foto' => $item->foto,
    
                //modelo 3D
                'modelo_3d' => $item->modelo_3d ?? null,
    
                'calidad' => [
                    'id' => $item->calidad->id ?? null,
                    'nombre' => $item->calidad->nombre ?? null,
                ],
    
                'armas' => $item->armas->map(function ($arma) {
                    return [
                        'item_id' => $arma->item_id,
    
                        'categoria' => [
                            'id' => $arma->categoria->id ?? null,
                            'nombre' => $arma->categoria->nombre ?? null,
                        ],
    
                        'exterior' => [
                            'id' => $arma->exterior->id ?? null,
                            'nombre' => $arma->exterior->nombre ?? null,
                        ],
    
                        // Añade información 3D y pegatinas del arma: modelo, textura y cada pegatina con su tipo
                        'modelo_3d' => $arma->modelo_3d ?? null,
                        'textura_3d' => $arma->textura_3d ?? null,
    
                        'pegatinas' => $arma->pegatinas->map(function ($p) {
                            return [
                                'id' => $p->id,
                                'precio' => $p->precio,
    
                                'modoPegatina' => [
                                    'id' => $p->modoPegatina->id ?? null,
                                    'nombre' => $p->modoPegatina->nombre ?? null,
                                ]
                            ];
                        }),
                    ];
                }),
            ],
    
            'exteriores' => $exteriores
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
            ->get()
            ->map(function ($arma) {
                return [
                    'id' => $arma->item->id,

                    'nombre' => $arma->item->nombre ?? null,
                    'foto' => $arma->item->foto ?? null,
                    'precio' => $arma->item->precio ?? null,
                    'categoria' => $arma->categoria->nombre ?? null,
                    'exterior' => $arma->exterior->nombre ?? null,

                    'pegatinas' => $arma->pegatinas->map(function ($p) {
                        return [
                            'nombre' => $p->modoPegatina->nombre ?? null
                        ];
                    })
                ];
            });

        return response()->json([
            'armas' => $armas
        ]);
    }

}
