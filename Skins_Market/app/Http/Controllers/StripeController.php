<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\User;

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
}