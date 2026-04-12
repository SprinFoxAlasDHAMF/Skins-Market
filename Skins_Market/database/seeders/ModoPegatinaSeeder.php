<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModoPegatinaSeeder extends Seeder
{
    public function run(): void
    {
        $modos = [
            'Normal',
            'Holográfica',
            'Foil',
            'Glitter',
            'Lenticular',
            'Gold',
        ];

        foreach ($modos as $modo) {
            DB::table('modo_pegatina')->updateOrInsert(
                ['nombre' => $modo],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info("Modo pegatinas creados correctamente");
    }
}