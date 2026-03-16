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
}