<?php
namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'nombre' => $request->name,
            'email' => $request->email,
            'contraseña' => Hash::make($request->password),
        ]);

        // Crear token automáticamente al registrarse
        $token = $user->createToken('react-token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado',
            'user' => $user,
            'token' => $token
        ], 201);
    }
}

