<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Arma;

class ItemAdminController extends Controller
{
    // Mostrar item para edición
    public function show($id)
    {
        $item = Item::with('arma')->findOrFail($id);
        return response()->json($item);
    }
    // Crear
    public function store(Request $request)
    {
        // Validación estricta: nada puede estar vacío si es requerido
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'tipo' => 'required|string|in:arma,guantes,agente,llavero',
            'calidad_id' => 'required|exists:calidad,id',
            'categoria_id' => 'required|exists:categoria,id',
            'exterior_id' => 'required|exists:exterior,id',
            'color' => 'nullable|string|max:50',
            'foto' => 'nullable|image|max:2048',
        ]);
        // Manejo de la foto
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('skins', 'public');
        }
        // Crear el Item
        $item = Item::create([
            'nombre' => $data['nombre'],
            'precio' => $data['precio'],
            'tipo' => $data['tipo'],
            'calidad_id' => $data['calidad_id'],
            'categoria_id' => $data['categoria_id'],
            'exterior_id' => $data['exterior_id'],
            'color' => $data['color'] ?? null,
            'foto' => $data['foto'] ?? null,
        ]);
        // Si es tipo arma, crear relación Arma
        if ($data['tipo'] === 'arma') {
            Arma::create([
                'item_id' => $item->id,
                'categoria_id' => $data['categoria_id'],
                'exterior_id' => $data['exterior_id'],
            ]);
        }
        return response()->json([
            'success' => true,
            'item' => $item,
        ]);
    }
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'tipo' => 'required|string|in:arma,guantes,agente,llavero',
            'calidad_id' => 'required|exists:calidad,id',
            'categoria_id' => 'required|exists:categoria,id',
            'exterior_id' => 'required|exists:exterior,id',
            'color' => 'nullable|string|max:50',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('skins', 'public');
        }

        $item->update($data);

        if ($data['tipo'] === 'arma') {
            Arma::updateOrCreate(
                ['item_id' => $item->id],
                [
                    'categoria_id' => $data['categoria_id'],
                    'exterior_id' => $data['exterior_id'],
                ]
            );
        }

        return response()->json(['success' => true, 'item' => $item]);
    }
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        // Borrar arma relacionada si existe
        $item->armas()->delete();

        $item->delete();

        return response()->json(['message' => 'Item eliminado']);
    }
}
