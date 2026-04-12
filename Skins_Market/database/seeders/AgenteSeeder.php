<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Agente;
use App\Models\Categoria;

class AgenteSeeder extends Seeder
{
    public function run()
    {
        // 🔎 Obtener categoría Agentes
        $categoria = Categoria::where('nombre', 'Agentes')->first();

        if (!$categoria) {
            $this->command->error("❌ Categoría 'Agentes' no existe");
            return;
        }

        // 🔎 Solo items tipo agente
        $items = Item::where('tipo', 'agente')->get();

        if ($items->isEmpty()) {
            $this->command->warn("⚠️ No hay items tipo agente para insertar");
            return;
        }

        foreach ($items as $item) {

            // 🧠 Insertar en tabla agentes usando item_id
            Agente::create([
                'item_id' => $item->id,
            ]);
        }

        $this->command->info("🪖 AgenteSeeder ejecutado correctamente");
    }
}