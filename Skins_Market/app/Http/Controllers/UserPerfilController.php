<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserPerfilController extends Controller
{
    public function show()
    {
        return response()->json(auth()->user());
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
}

