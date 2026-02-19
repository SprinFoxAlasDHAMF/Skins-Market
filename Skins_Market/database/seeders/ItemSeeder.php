<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Arma;
use App\Models\Calidad;
use App\Models\Categoria;
use App\Models\Exterior;

class ItemSeeder extends Seeder
{
    public function run()
    {
        $calidades = Calidad::all();
        $categorias = Categoria::all();
        $exteriores = Exterior::all();

        if ($calidades->isEmpty() || $categorias->isEmpty() || $exteriores->isEmpty()) {
            throw new \Exception('Las tablas de calidad, categoría o exterior están vacías. Crea primero esos seeders.');
        }

        // =========================
        // Definición de skins
        // =========================
        $skins = [
            [
                'nombre' => 'AK-47 | Redline',
                'color' => 'Rojo',
                'precio' => 45.00,
                'tipo' => 'arma',
                'calidad' => 'Mil-Spec',
                'categoria' => 'Rifles',
                'exteriores' => ['Minimal Wear', 'Factory New'],
            ],
            [
                'nombre' => 'M4A4 | Howl',
                'color' => 'Naranja',
                'precio' => 150.00,
                'tipo' => 'arma',
                'calidad' => 'Contraband',
                'categoria' => 'Rifles',
                'exteriores' => ['Minimal Wear', 'Factory New'],
            ],
            [
                'nombre' => 'AWP | Dragon Lore',
                'color' => 'Beige',
                'precio' => 1800.00,
                'tipo' => 'arma',
                'calidad' => 'Contraband',
                'categoria' => 'Snipers',
                'exteriores' => ['Field-Tested', 'Factory New'],
            ],
        ];

        // =========================
        // Crear items y armas
        // =========================
        foreach ($skins as $skin) {
            $calidad = $calidades->where('nombre', $skin['calidad'])->first();
            $categoria = $categorias->where('nombre', $skin['categoria'])->first();

            if (!$calidad || !$categoria) {
                echo "No se pudo crear {$skin['nombre']}. Falta calidad o categoría.\n";
                continue;
            }

            foreach ($skin['exteriores'] as $nombreExterior) {
                $exterior = $exteriores->where('nombre', $nombreExterior)->first();
                if (!$exterior) {
                    echo "No existe el exterior {$nombreExterior} para {$skin['nombre']}.\n";
                    continue;
                }

                // Crear item
                $item = Item::create([
                    'nombre' => $skin['nombre'],
                    'color' => $skin['color'],
                    'precio' => $skin['precio'],
                    'foto' => null,
                    'tipo' => $skin['tipo'],
                    'calidad_id' => $calidad->id,
                    'categoria_id' => $categoria->id,
                    'exterior_id' => $exterior->id,
                ]);

                // Crear relación en tabla armas
                Arma::create([
                    'item_id' => $item->id,
                    'categoria_id' => $categoria->id,
                    'exterior_id' => $exterior->id,
                ]);
            }
        }

        $this->command->info('Seeder ejecutado correctamente: Items con varias variantes de exterior creados.');
    }
}
