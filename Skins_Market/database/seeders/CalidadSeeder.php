<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Calidad;

class CalidadSeeder extends Seeder
{
    public function run()
    {
        $calidades = [
            'Consumer Grade',   // Blanco
            'Industrial Grade', // Azul claro
            'Mil-Spec',         // Azul
            'Restricted',       // Morado
            'Classified',       // Rosa
            'Covert',           // Rojo
            'Contraband',       // Naranja
        ];

        foreach ($calidades as $nombre) {
            Calidad::firstOrCreate(['nombre' => $nombre]);
        }

        $this->command->info('Seeder de calidades creado correctamente.');
    }
}
