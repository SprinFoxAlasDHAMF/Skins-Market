<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Llavero;
use App\Models\Categoria;

class LlaveroSeeder extends Seeder
{
    public function run()
    {
        // 🔎 Obtener categoría Llaveros
        $categoria = Categoria::where('nombre', 'Llaveros')->first();

        if (!$categoria) {
            $this->command->error("❌ Categoría 'Llaveros' no existe");
            return;
        }

        // 🔑 Solo items tipo llavero
        $items = Item::where('tipo', 'llavero')->get();

        if ($items->isEmpty()) {
            $this->command->warn("⚠️ No hay items tipo llavero para insertar");
            return;
        }

        foreach ($items as $item) {

            // 🧠 Insertar en tabla llaveros usando item_id
            Llavero::create([
                'item_id' => $item->id,
            ]);
        }

        $this->command->info("🔑 LlaveroSeeder ejecutado correctamente");
    }
}