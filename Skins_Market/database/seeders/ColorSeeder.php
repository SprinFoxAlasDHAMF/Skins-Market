<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colores = [
            'rojo',
            'azul',
            'verde',
            'amarillo',
            'negro',
            'blanco',
            'morado',
            'camuflaje'
        ];

        foreach ($colores as $color) {
            DB::table('colors')->updateOrInsert(
                ['nombre' => $color],
                [
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}