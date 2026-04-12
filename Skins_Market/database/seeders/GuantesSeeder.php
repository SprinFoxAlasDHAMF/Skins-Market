<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuantesSeeder extends Seeder
{
    public function run(): void
    {
        // 🔍 coger solo items tipo guantes
        $guantesItems = DB::table('item')->where('tipo', 'guantes')->get();

        if ($guantesItems->isEmpty()) {
            $this->command->error("No hay guantes en la tabla item.");
            return;
        }

        $insert = [];

        foreach ($guantesItems as $item) {

            // 👇 cada guante ya tiene exterior_id en item
            $insert[] = [
                'item_id' => $item->id,
                'exterior_id' => $item->exterior_id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('guantes')->insert($insert);

        $this->command->info("🔥 Guantes insertados correctamente: " . count($insert));
    }
}