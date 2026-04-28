<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Transfer;

class StripeController extends Controller
{
    public function depositar(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no autenticado.',
            ], 401);
        }

        $stripeSecret = config('services.stripe.secret');

        if (empty($stripeSecret)) {
            return response()->json([
                'message' => 'La clave secreta de Stripe no esta configurada en el servidor.',
            ], 500);
        }

        try {
            Stripe::setApiKey($stripeSecret);

            $amountInCents = (int) round(((float) $request->amount) * 100);

            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => 'eur',
                'metadata' => [
                    'usuario_id' => (string) $user->id,
                ],
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Error al crear PaymentIntent de Stripe', [
                'user_id' => $user->id,
                'amount' => $request->amount,
                'stripe_error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'No se pudo contactar con Stripe desde el servidor. Revisa la conexion saliente del backend.',
                'error' => $e->getMessage(),
            ], 502);
        } catch (\Throwable $e) {
            Log::error('Error inesperado al iniciar un deposito', [
                'user_id' => $user->id,
                'amount' => $request->amount,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Ocurrio un error inesperado al iniciar el pago.',
            ], 500);
        }
    }

    public function confirmarDeposito(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no autenticado.',
            ], 401);
        }

        $stripeSecret = config('services.stripe.secret');

        if (empty($stripeSecret)) {
            return response()->json([
                'message' => 'La clave secreta de Stripe no esta configurada en el servidor.',
            ], 500);
        }

        try {
            Stripe::setApiKey($stripeSecret);

            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

            if ($paymentIntent->status !== 'succeeded') {
                return response()->json([
                    'message' => 'El pago no ha sido completado.',
                ], 400);
            }

            if ((string) ($paymentIntent->metadata->usuario_id ?? '') !== (string) $user->id) {
                return response()->json([
                    'message' => 'El pago no pertenece al usuario autenticado.',
                ], 403);
            }

            $monto = $paymentIntent->amount / 100;
            $user->amount = ($user->amount ?? 0) + $monto;
            $user->save();

            return response()->json([
                'message' => 'Deposito confirmado.',
                'nuevo_saldo' => $user->amount,
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Error de Stripe al confirmar deposito', [
                'user_id' => $user->id,
                'payment_intent_id' => $request->payment_intent_id,
                'stripe_error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'No se pudo verificar el pago con Stripe desde el servidor.',
                'error' => $e->getMessage(),
            ], 502);
        } catch (\Throwable $e) {
            Log::error('Error inesperado al confirmar deposito', [
                'user_id' => $user->id,
                'payment_intent_id' => $request->payment_intent_id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Error al confirmar el deposito.',
            ], 500);
        }
    }

    public function retirar(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = auth()->user();

        if ($user->balance < $request->amount) {
            return response()->json(['message' => 'Saldo insuficiente'], 400);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        if (!$user->stripe_account_id) {
            return response()->json(['message' => 'Cuenta de Stripe no conectada'], 400);
        }

        try {
            $transfer = Transfer::create([
                'amount' => $request->amount * 100,
                'currency' => 'usd',
                'destination' => $user->stripe_account_id,
            ]);

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

        $accountLink = \Stripe\AccountLink::create([
            'account' => $request->user()->stripe_account_id,
            'refresh_url' => route('stripe.account_refresh'),
            'return_url' => route('stripe.account_return'),
            'type' => 'account_onboarding',
        ]);

        return redirect($accountLink->url);
    }

    public function confirmarCompra(Request $request)
    {
        $user = auth()->user();

        return DB::transaction(function () use ($user) {
            $carrito = DB::table('carrito')
                ->where('usuario_id', $user->id)
                ->get();

            if ($carrito->isEmpty()) {
                return response()->json([
                    'message' => 'El carrito esta vacio'
                ], 400);
            }

            $items = DB::table('item')
                ->whereIn('id', $carrito->pluck('item_id'))
                ->get()
                ->keyBy('id');

            $total = 0;

            foreach ($carrito as $c) {
                $precio = $items[$c->item_id]->precio;
                $total += $precio * $c->cantidad;
            }

            if ($user->dinero < $total) {
                return response()->json([
                    'message' => 'Saldo insuficiente'
                ], 400);
            }

            $facturaId = DB::table('factura')->insertGetId([
                'usuario_id' => $user->id,
                'fecha' => Carbon::now(),
                'total' => $total,
                'metodo_pago' => 'wallet',
                'estado' => 'pagado',
            ]);

            foreach ($carrito as $c) {
                $item = $items[$c->item_id];
                $subtotal = $item->precio * $c->cantidad;

                DB::table('factura_detalle')->insertGetId([
                    'factura_id' => $facturaId,
                    'item_id' => $item->id,
                    'cantidad' => $c->cantidad,
                    'precio_unitario' => $item->precio,
                    'subtotal' => $subtotal,
                ]);

                for ($i = 0; $i < $c->cantidad; $i++) {
                    DB::table('token')->insert([
                        'factura_id' => $facturaId,
                        'item_id' => $item->id,
                        'token' => Str::uuid(),
                        'fecha_creacion' => Carbon::now(),
                    ]);
                }
            }

            DB::table('usuarios')
                ->where('id', $user->id)
                ->update([
                    'dinero' => $user->dinero - $total
                ]);

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
