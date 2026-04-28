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
        if ($request->filled('tiene_pegatinas')) {

            if ($request->tiene_pegatinas == "1") {
                $query->whereHas('armas.pegatinas');
            }

            if ($request->tiene_pegatinas == "0") {
                $query->whereDoesntHave('armas.pegatinas');
            }
        }
        $items = $query->get()->map(function ($item) {
            $arma = $item->armas->first();
        
            return [
                'id' => $item->id,
                'nombre' => $item->nombre,
                'color' => $item->color?->nombre,
                'precio' => $item->precio,
                'foto' => $item->foto,
                'calidad' => $item->calidad?->nombre,
                'categoria' => $arma?->categoria?->nombre,
                'exterior' => $arma?->exterior?->nombre,
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
    
        $exteriores = Exterior::all()->map(fn($ext) => [
            'id' => $ext->id,
            'nombre' => $ext->nombre
        ]);
    
        return response()->json([
            'item' => [
                'id' => $item->id,
                'nombre' => $item->nombre,
                'precio' => $item->precio,
                'foto' => $item->foto,
                'modelo_3d' => $item->modelo_3d ?? null,
    
                'calidad' => [
                    'id' => $item->calidad?->id,
                    'nombre' => $item->calidad?->nombre,
                ],
    
                'armas' => $item->armas->map(function ($arma) {
                    return [
                        'id' => $arma->item_id,
    
                        'nombre' => $arma->item->nombre ?? null,
                        'foto' => $arma->item->foto ?? null,
                        'precio' => $arma->item->precio ?? null,
    
                        'categoria' => [
                            'id' => $arma->categoria?->id,
                            'nombre' => $arma->categoria?->nombre,
                        ],
    
                        'exterior' => [
                            'id' => $arma->exterior?->id,
                            'nombre' => $arma->exterior?->nombre,
                        ],
    
                        'modelo_3d' => $arma->modelo_3d ?? null,
                        'textura_3d' => $arma->textura_3d ?? null,
    
                        // 🔥 PEGATINAS FIXED (IMPORTANTE)
                        'pegatinas' => $arma->pegatinas->map(function ($p) {
                            return [
                                'id' => $p->id,
                                'nombre' => $p->nombre ?? null,   // 🔥 AÑADE ESTO
                                'imagen' => $p->imagen ?? null,   // 🔥 AÑADE ESTO
                                'precio' => $p->precio,
                        
                                'modoPegatina' => [
                                    'id' => $p->modoPegatina->id ?? null,
                                    'nombre' => $p->modoPegatina->nombre ?? null,
                                ]
                            ];
                        }),
                    ];
                })->values(),
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
                ->unique('item_id')   // 👈 CLAVE
                ->values()
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
                                'id' => $p->id,
                                'nombre' => $p->nombre,
                                'precio' => $p->precio,
                                'imagen' => $p->imagen,
                                'modoPegatina' => [
                                    'id' => $p->modoPegatina?->id,
                                    'nombre' => $p->modoPegatina?->nombre,
                                ]
                            ];
                        })->values(),
                    ];
                });

        return response()->json([
            'armas' => $armas
        ]);
    }

}
