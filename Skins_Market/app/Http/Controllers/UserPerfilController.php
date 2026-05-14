<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\Balance;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class UserPerfilController extends Controller
{
    public function show()
    {
        $usuario = auth()->user();
        $saldoStripe = $this->obtenerSaldoDisponibleStripe($usuario);

        return response()->json(array_merge(
            $usuario->toArray(),
            [
                'saldo' => $saldoStripe['amount'],
                'saldo_disponible' => $saldoStripe['amount'],
                'saldo_disponible_centavos' => $saldoStripe['amount_in_cents'],
                'saldo_moneda' => $saldoStripe['currency'],
                'saldo_fuente' => $saldoStripe['source'],
                'email_verificado' => !is_null($usuario->email_verified_at),

            ]
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'foto' => 'image|mimes:jpg,png|max:2048'
        ]);

        $usuario = auth()->user();

        $usuario->nombre = $request->nombre;

        if ($request->hasFile('foto')) {
            $ruta = $request->file('foto')->store('perfiles', 'public');
            $usuario->foto_perfil = $ruta;
        }

        $usuario->save();

        return response()->json([
            'message' => 'Perfil actualizado',
            'user' => $usuario
        ]);
    }

    // Agregar o quitar favorito
    public function toggleFavorito(Request $request, $item_id)
    {
        $usuario = auth()->user();

        if ($usuario->favoritos()->where('item_id', $item_id)->exists()) {
            $usuario->favoritos()->detach($item_id);
            $mensaje = 'Eliminado de favoritos';
        } else {
            $usuario->favoritos()->attach($item_id);
            $mensaje = 'Agregado a favoritos';
        }

        return response()->json([
            'message' => $mensaje,
            'favoritos' => $usuario->favoritos()->pluck('item_id') // devuelve ids
        ]);
    }

    // Listar favoritos del usuario
    public function favoritos()
    {
        $usuario = auth()->user();
        $items = $usuario->favoritos()->with(['calidad', 'armas.exterior', 'armas.categoria', 'color'])->get();

        // Transformar al mismo formato que SkinController::index()
        $favoritos = $items->map(function ($item) {
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

        return response()->json($favoritos);
    }
    //Funcion para ver las compras del usuario
    public function compras()
    {
        $usuario = auth()->user();

        // Traer facturas con detalles e items
        $facturas = $usuario->facturas()->with(['detalles.item.calidad', 'detalles.item.armas.exterior', 'detalles.item.armas.categoria'])->get();

        $compras = $facturas->map(function($factura) {
            return [
                'id' => $factura->id,
                'fecha' => $factura->fecha,
                'total' => $factura->total,
                'estado' => $factura->estado,
                'metodo_pago' => $factura->metodo_pago,
                'items' => $factura->detalles->map(function($detalle) {
                    $arma = $detalle->item->armas->first();
                    return [
                        'item_id' => $detalle->item->id,
                        'nombre' => $detalle->item->nombre,
                        'precio_unitario' => $detalle->precio_unitario,
                        'subtotal' => $detalle->subtotal,
                        'cantidad' => $detalle->cantidad,
                        'foto' => $detalle->item->foto,
                        'modelo_3d' => $detalle->item->modelo_3d ?? null,
                        'calidad' => $detalle->item->calidad->nombre ?? null,
                        'categoria' => $arma?->categoria->nombre ?? null,
                        'exterior' => $arma?->exterior->nombre ?? null,
                    ];
                })
            ];
        });

        return response()->json($compras);
    }
    
    //Funcion para ver lo que un usuario posee
    public function inventario()
    {
        $usuario = auth()->user();

        // Traemos todos los detalles de facturas del usuario, con item y relaciones necesarias
        $detalles = \App\Models\FacturaDetalle::with([
            'item.calidad',
            'item.armas.exterior',
            'item.armas.categoria',
            'item.armas.pegatinas.modoPegatina'
        ])->whereHas('factura', function ($q) use ($usuario) {
            $q->where('usuario_id', $usuario->id)
            ->where('estado', 'completada'); // solo items comprados efectivamente
        })->get();

        // Agrupar por item_id para no repetir el mismo item comprado varias veces
        $inventario = $detalles->groupBy('item_id')->map(function ($detallesItem) {
            $detalle = $detallesItem->first();
            $item = $detalle->item;
            $arma = $item->armas->first();

            return [
                'item_id' => $item->id,
                'nombre' => $item->nombre,
                'foto' => $item->foto,
                'modelo_3d' => $item->modelo_3d ?? null,
                'calidad' => $item->calidad->nombre ?? null,
                'categoria' => $arma?->categoria->nombre ?? null,
                'exterior' => $arma?->exterior->nombre ?? null,
                'pegatinas' => $arma?->pegatinas->map(function($p){
                    return [
                        'id' => $p->id,
                        'precio' => $p->precio,
                        'modo' => [
                            'id' => $p->modoPegatina->id ?? null,
                            'nombre' => $p->modoPegatina->nombre ?? null
                        ]
                    ];
                }) ?? [],
            ];
        })->values();

        return response()->json([
            'usuario_id' => $usuario->id,
            'nombre' => $usuario->nombre,
            'inventario' => $inventario
        ]);
    }

    private function obtenerSaldoDisponibleStripe(User $usuario): array
    {
        $saldoLocal = (float) ($usuario->amount ?? 0);

        if (empty($usuario->stripe_account_id) || empty(config('services.stripe.secret'))) {
            return [
                'amount' => round($saldoLocal, 2),
                'amount_in_cents' => (int) round($saldoLocal * 100),
                'currency' => 'usd',
                'source' => 'local',
            ];
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $balance = Balance::retrieve([], [
                'stripe_account' => $usuario->stripe_account_id,
            ]);

            $balancesDisponibles = collect($balance->available ?? []);
            $saldoPreferido = $balancesDisponibles->firstWhere('currency', 'usd')
                ?? $balancesDisponibles->firstWhere('currency', 'eur')
                ?? $balancesDisponibles->first();

            if (!$saldoPreferido) {
                return [
                    'amount' => 0.0,
                    'amount_in_cents' => 0,
                    'currency' => 'usd',
                    'source' => 'stripe',
                ];
            }

            return [
                'amount' => round($saldoPreferido->amount / 100, 2),
                'amount_in_cents' => (int) $saldoPreferido->amount,
                'currency' => strtolower($saldoPreferido->currency),
                'source' => 'stripe',
            ];
        } catch (ApiErrorException $e) {
            return [
                'amount' => round($saldoLocal, 2),
                'amount_in_cents' => (int) round($saldoLocal * 100),
                'currency' => 'usd',
                'source' => 'local_fallback',
                'stripe_error' => $e->getMessage(),
            ];
        }
    }
}

