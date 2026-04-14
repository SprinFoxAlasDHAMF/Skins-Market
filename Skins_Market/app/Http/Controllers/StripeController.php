<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
class StripeController extends Controller
{
    public function depositar(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'amount' => 'required|numeric|min:1',  // Mínimo 1 unidad de dinero
        ]);


        // Obtener el usuario autenticado
        $user = auth()->user();


        // Configurar Stripe con tu clave secreta
        Stripe::setApiKey(env('STRIPE_SECRET'));


        // Crear el PaymentIntent para el depósito
        $paymentIntent = PaymentIntent::create([
            'amount' => $request->amount * 100,  // Convertir a centavos
            'currency' => 'usd',  // O la moneda que prefieras
            'metadata' => [
                'usuario_id' => $user->id,
            ],
        ]);


        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }


    public function retirar(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'amount' => 'required|numeric|min:1',  // Mínimo 1 unidad de dinero
        ]);


        // Obtener el usuario autenticado
        $user = auth()->user();


        // Validar que el usuario tenga suficiente saldo
        if ($user->balance < $request->amount) {
            return response()->json(['message' => 'Saldo insuficiente'], 400);
        }


        // Configurar Stripe con tu clave secreta
        Stripe::setApiKey(env('STRIPE_SECRET'));


        // Verificar si el usuario tiene una cuenta Stripe conectada
        if (!$user->stripe_account_id) {
            return response()->json(['message' => 'Cuenta de Stripe no conectada'], 400);
        }


        // Crear un Transfer (retirar dinero de Stripe Connect a la cuenta bancaria del usuario)
        try {
            $transfer = Transfer::create([
                'amount' => $request->amount * 100,  // Convertir a centavos
                'currency' => 'usd',  // Puedes modificar esto según la moneda
                'destination' => $user->stripe_account_id,  // Cuenta conectada de Stripe
            ]);


            // Actualizar el saldo del usuario
            $user->balance -= $request->amount;
            $user->save();


            return response()->json([
                'message' => 'Retiro exitoso',
                'transfer' => $transfer,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar el retiro', 'error' => $e->getMessage()], 500);
        }
    }
   
    public function conectarCuentaStripe(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));


        // Redirigir al usuario a Stripe para completar la conexión
        $accountLink = \Stripe\AccountLink::create([
            'account' => $request->user()->stripe_account_id, // ID de la cuenta conectada de Stripe
            'refresh_url' => route('stripe.account_refresh'),  // URL para refrescar en caso de error
            'return_url' => route('stripe.account_return'),  // URL de retorno después de la conexión exitosa
            'type' => 'account_onboarding', // Tipo de enlace
        ]);


        return redirect($accountLink->url);
    }


    public function confirmarCompra(Request $request)
    {
        $user = auth()->user();


        return DB::transaction(function () use ($user) {


            // 1. Obtener carrito del usuario
            $carrito = DB::table('carrito')
                ->where('usuario_id', $user->id)
                ->get();


            if ($carrito->isEmpty()) {
                return response()->json([
                    'message' => 'El carrito está vacío'
                ], 400);
            }


            // 2. Obtener info de items
            $items = DB::table('item')
                ->whereIn('id', $carrito->pluck('item_id'))
                ->get()
                ->keyBy('id');


            // 3. Calcular total REAL
            $total = 0;


            foreach ($carrito as $c) {
                $precio = $items[$c->item_id]->precio;
                $total += $precio * $c->cantidad;
            }


            // 4. Verificar saldo
            if ($user->dinero < $total) {
                return response()->json([
                    'message' => 'Saldo insuficiente'
                ], 400);
            }


            // 5. Crear factura
            $facturaId = DB::table('factura')->insertGetId([
                'usuario_id' => $user->id,
                'fecha' => Carbon::now(),
                'total' => $total,
                'metodo_pago' => 'wallet',
                'estado' => 'pagado',
            ]);


            // 6. Crear detalles + tokens
            foreach ($carrito as $c) {


                $item = $items[$c->item_id];
                $subtotal = $item->precio * $c->cantidad;


                // factura_detalle
                $detalleId = DB::table('factura_detalle')->insertGetId([
                    'factura_id' => $facturaId,
                    'item_id' => $item->id,
                    'cantidad' => $c->cantidad,
                    'precio_unitario' => $item->precio,
                    'subtotal' => $subtotal,
                ]);


                // generar tokens (uno por unidad)
                for ($i = 0; $i < $c->cantidad; $i++) {


                    DB::table('token')->insert([
                        'factura_id' => $facturaId,
                        'item_id' => $item->id,
                        'token' => Str::uuid(),
                        'fecha_creacion' => Carbon::now(),
                    ]);
                }
            }


            // 7. Restar saldo
            DB::table('usuarios')
                ->where('id', $user->id)
                ->update([
                    'dinero' => $user->dinero - $total
                ]);


            // 8. Vaciar carrito
            DB::table('carrito')
                ->where('usuario_id', $user->id)
                ->delete();


            return response()->json([
                'message' => 'Compra realizada correctamente',
                'factura_id' => $facturaId,
                'total' => $total
            ]);
        });
    }
}
