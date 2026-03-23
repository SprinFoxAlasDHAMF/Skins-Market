<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrito;
use App\Models\Item;

class CarritoController extends Controller
{
    // Ver carrito
    public function index()
    {
        $usuario = auth()->user();
        $carrito = Carrito::with('item.calidad', 'item.armas.exterior', 'item.armas.categoria')
                          ->where('usuario_id', $usuario->id)
                          ->get();

        return response()->json($carrito);
    }

    // Agregar item
    public function agregar(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:item,id',
            'cantidad' => 'integer|min:1'
        ]);

        $usuario = auth()->user();

        $carrito = Carrito::updateOrCreate(
            ['usuario_id' => $usuario->id, 'item_id' => $request->item_id],
            ['cantidad' => $request->cantidad ?? 1]
        );

        return response()->json(['message' => 'Item agregado al carrito', 'carrito' => $carrito]);
    }

    // Actualizar cantidad
    public function actualizar(Request $request, $item_id)
    {
        $request->validate(['cantidad' => 'required|integer|min:1']);
        $usuario = auth()->user();

        $carrito = Carrito::where('usuario_id', $usuario->id)
                          ->where('item_id', $item_id)
                          ->firstOrFail();

        $carrito->cantidad = $request->cantidad;
        $carrito->save();

        return response()->json(['message' => 'Cantidad actualizada', 'carrito' => $carrito]);
    }

    // Eliminar item
    public function eliminar($item_id)
    {
        $usuario = auth()->user();

        Carrito::where('usuario_id', $usuario->id)
               ->where('item_id', $item_id)
               ->delete();

        return response()->json(['message' => 'Item eliminado del carrito']);
    }

    public function checkout()
    {
        $usuario = auth()->user();

        $carrito = \App\Models\Carrito::with('item')->where('usuario_id', $usuario->id)->get();

        if ($carrito->isEmpty()) {
            return response()->json(['message' => 'El carrito está vacío'], 400);
        }

        // Calcular total
        $total = $carrito->sum(function ($c) {
            return $c->item->precio * $c->cantidad;
        });

        // Verificar saldo
        if ($usuario->dinero < $total) {
            return response()->json([
                'message' => 'Saldo insuficiente, por favor añade fondos',
                'saldo_actual' => $usuario->dinero,
                'total_carrito' => $total
            ], 400);
        }

        // Reducir saldo del usuario s
        $usuario->dinero -= $total;
        $usuario->save();

        // Crear factura
        $factura = \App\Models\Factura::create([
            'usuario_id' => $usuario->id,
            'fecha' => now(),
            'total' => $total,
            'metodo_pago' => 'balance',
            'estado' => 'completada'
        ]);

        // Crear detalles de factura 
        foreach ($carrito as $c) {
            \App\Models\FacturaDetalle::create([
                'factura_id' => $factura->id,
                'item_id' => $c->item_id,
                'cantidad' => $c->cantidad,
                'precio_unitario' => $c->item->precio,
                'subtotal' => $c->item->precio * $c->cantidad
            ]);
        }

        // Vaciar carrito
        \App\Models\Carrito::where('usuario_id', $usuario->id)->delete();

        return response()->json([
            'message' => 'Compra realizada exitosamente',
            'factura_id' => $factura->id,
            'total' => $total
        ]);
    }
}