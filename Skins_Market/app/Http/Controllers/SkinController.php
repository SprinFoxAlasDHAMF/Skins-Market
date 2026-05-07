<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Arma;
use App\Models\Calidad;
use App\Models\Categoria;
use App\Models\Exterior;
use App\Models\Color;
use Illuminate\Support\Facades\DB;
class SkinController extends Controller
{
    public function index(Request $request)
{
    try {
            $nombre = $request->input('nombre');
            $conPegatinas = $request->input('tiene_pegatinas'); // Asegúrate que coincida con el nombre del frontend

            // --- 1. CONSULTA DE SKINS (item) ---
            $skinsQuery = DB::table('item')
                ->leftJoin('calidad', 'item.calidad_id', '=', 'calidad.id')
                // Paso 1: Unimos item con la tabla arma (asumiendo que arma tiene item_id)
                ->leftJoin('arma', 'item.id', '=', 'arma.item_id')
                // Paso 2: Unimos con la tabla pivote que encontraste
                ->leftJoin('arma_pegatina', 'arma.id', '=', 'arma_pegatina.arma_id')
                ->select(
                    'item.id',
                    'item.nombre',
                    'item.precio',
                    'item.foto as imagen',
                    DB::raw("'skin' as tipo_item"),
                    'calidad.nombre as info_extra',
                    'item.calidad_id',
                    'item.tipo',
                    'item.categoria_id',
                    'item.exterior_id',
                    // Contamos las pegatinas usando la tabla correcta
                    DB::raw('COUNT(arma_pegatina.pegatina_id) as total_pegatinas')
                )
                ->groupBy(
                    'item.id', 'item.nombre', 'item.precio', 'item.foto', 
                    'calidad.nombre', 'item.calidad_id', 'item.tipo', 
                    'item.categoria_id', 'item.exterior_id'
                );

            // --- FILTRO DE PEGATINAS ---
            if ($request->has('tiene_pegatinas')) {
                $val = $request->input('tiene_pegatinas');
                if ($val === 'si' || $val === '1') {
                    $skinsQuery->having('total_pegatinas', '>', 0);
                } elseif ($val === 'no' || $val === '0') {
                    $skinsQuery->having('total_pegatinas', '=', 0);
                }
            }

            // --- 2. CONSULTA DE PEGATINAS (Tienda de pegatinas sueltas) ---
            $pegatinasQuery = DB::table('pegatina')
                ->leftJoin('modo_pegatina', 'pegatina.modo_pegatina_id', '=', 'modo_pegatina.id')
                ->select(
                    'pegatina.id',
                    'pegatina.nombre',
                    'pegatina.precio',
                    'pegatina.imagen',
                    DB::raw("'pegatina' as tipo_item"),
                    'modo_pegatina.nombre as info_extra',
                    DB::raw('NULL as calidad_id'),
                    DB::raw('NULL as tipo'),
                    DB::raw('NULL as categoria_id'),
                    DB::raw('NULL as exterior_id'),
                    DB::raw('0 as total_pegatinas')
                );

            // --- 3. FILTROS GLOBALES ---
            if ($request->filled('nombre')) {
                $skinsQuery->where('item.nombre', 'LIKE', "%$nombre%");
                $pegatinasQuery->where('pegatina.nombre', 'LIKE', "%$nombre%");
            }
            if ($request->filled('precio_min')) {
                $skinsQuery->where('item.precio', '>=', $request->precio_min);
                $pegatinasQuery->where('pegatina.precio', '>=', $request->precio_min);
            }
            if ($request->filled('precio_max')) {
                $skinsQuery->where('item.precio', '<=', $request->precio_max);
                $pegatinasQuery->where('pegatina.precio', '<=', $request->precio_max);
            }
            if ($request->filled('color_id')) {
                $skinsQuery->where('item.color_id', $request->color_id);

                $pegatinasQuery->whereRaw('1 = 0'); 
            }
            // --- 4. LÓGICA DE UNIÓN ---
            // Si hay filtros de armas o pegatinas puestas, descartamos las pegatinas sueltas
            $soloArmas = $request->filled('calidad_id') || $request->filled('tipo') || 
                        $request->filled('categoria_id') || $request->filled('exterior_id') || 
                        $request->filled('tiene_pegatinas');
            
            $soloPegatinasIndividuales = $request->filled('modo_pegatina_id');

            if ($soloArmas) {
                if($request->filled('calidad_id')) $skinsQuery->where('item.calidad_id', $request->calidad_id);
                if($request->filled('tipo')) $skinsQuery->where('item.tipo', $request->tipo);
                if($request->filled('categoria_id')) $skinsQuery->where('item.categoria_id', $request->categoria_id);
                if($request->filled('exterior_id')) $skinsQuery->where('item.exterior_id', $request->exterior_id);
                $queryFinal = $skinsQuery;
            } elseif ($soloPegatinasIndividuales) {
                $pegatinasQuery->where('pegatina.modo_pegatina_id', $request->modo_pegatina_id);
                $queryFinal = $pegatinasQuery;
            } else {
                $queryFinal = $skinsQuery->unionAll($pegatinasQuery);
            }

        $orderBy = $request->input('order_by', 'id'); // Cambiado el default a id
        $orderDir = $request->input('order_dir', 'asc');

        // Validar columnas permitidas
        if (!in_array($orderBy, ['precio', 'nombre', 'id'])) {
            $orderBy = 'id';
        }

        // --- 5. EJECUCIÓN CON PAGINACIÓN Y ORDEN ---
        $resultados = DB::table(DB::raw("({$queryFinal->toSql()}) as resultados_unidos"))
            ->mergeBindings($queryFinal)
            ->orderBy($orderBy, $orderDir) // 👈 Aplicamos el orden aquí
            ->paginate($request->get('per_page', 20));

        return response()->json($resultados);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error en la consulta',
                'detalle' => $e->getMessage()
            ], 500);
        }
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
