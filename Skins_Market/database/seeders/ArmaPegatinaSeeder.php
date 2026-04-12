<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArmaPegatinaSeeder extends Seeder
{
    public function run(): void
    {
        $armas = DB::table('item')->where('tipo', 'arma')->pluck('id');
        $pegatinas = DB::table('pegatina')->pluck('id');

        if ($armas->isEmpty() || $pegatinas->isEmpty()) {
            $this->command->error("No hay armas o pegatinas disponibles.");
            return;
        }

        // ======================================================
        // ASIGNACIONES REALISTAS (tipo CS2)
        // ======================================================
        $relaciones = [];

        foreach ($armas as $arma_id) {

            // 1 a 3 pegatinas aleatorias por arma
            $randomPegatinas = $pegatinas->random(rand(1, 3));

            foreach ($randomPegatinas as $pegatina_id) {
                $relaciones[] = [
                    'arma_id' => $arma_id,
                    'pegatina_id' => $pegatina_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('arma_pegatina')->insert($relaciones);

        $this->command->info("🔥 Armas con pegatinas creadas correctamente: " . count($relaciones));
    }
}