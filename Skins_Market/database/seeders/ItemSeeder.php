<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Arma;
use App\Models\Calidad;
use App\Models\Categoria;
use App\Models\Exterior;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run()
    {
        $calidades = Calidad::all();
        $categorias = Categoria::all();
        $exteriores = Exterior::all();
        $colores = DB::table('colors')->get();

        $getColor = fn($n) => $colores->firstWhere('nombre', $n)?->id;
        $getCat = fn($n) => $categorias->firstWhere('nombre', $n)?->id;
        $getCalidad = fn($n) => $calidades->firstWhere('nombre', $n)?->id;

        // ======================================================
        // 🔫 PISTOLAS
        // ======================================================
        $pistolas = [
            ['nombre'=>'Glock-18 | Fade','color'=>'morado','precio'=>250,'foto'=>'items/armas/glock-18-fade_factory_new.png','exterior_id'=>1],
            ['nombre'=>'Glock-18 | Fade','color'=>'morado','precio'=>150,'foto'=>'items/armas/glock-18-fade_factory_new.png','exterior_id'=>3],
            ['nombre'=>'USP-S | Kill Confirmed','color'=>'rojo','precio'=>120,'foto'=>'items/armas/Kill-Confirmed_factory_new.png','exterior_id'=>1],
            ['nombre'=>'Desert Eagle | Blaze','color'=>'amarillo','precio'=>300,'foto'=>'items/armas/desert_eagle_factory_new.png','exterior_id'=>1],
            ['nombre'=>'P2000 | Fire Elemental','color'=>'rojo','precio'=>80,'foto'=>'items/armas/Fire_Elemental_Factory_new.png','exterior_id'=>1],
            ['nombre'=>'Five-SeveN | Hyper Beast','color'=>'morado','precio'=>60,'foto'=>'items/armas/Five-Seven_Hyper-beast_Factory_new.png','exterior_id'=>1],
        ];

        // ======================================================
        // 🔫 RIFLES
        // ======================================================
        $rifles = [
            ['nombre'=>'AK-47 | Fire Serpent','color'=>'verde','precio'=>900,'foto'=>'items/armas/AK-Fire_Serpent_Factory_new.png','exterior_id'=>1],
            ['nombre'=>'AK-47 | Redline','color'=>'rojo','precio'=>45,'foto'=>'items/armas/AK-Redline_Factory_new.png','exterior_id'=>1],
            ['nombre'=>'M4A4 | Howl','color'=>'rojo','precio'=>2500,'foto'=>'items/armas/M4A4-Howl_Factory_new.png','exterior_id'=>1],
            ['nombre'=>'M4A1-S | Printstream','color'=>'blanco','precio'=>300,'foto'=>'items/armas/M4A1-S_Printstream_Factory_new.png','exterior_id'=>1],
            ['nombre'=>'AUG | Akihabara Accept','color'=>'rojo','precio'=>1200,'foto'=>'items/armas/AUG_Akihabara_Accept_Factory_new.png','exterior_id'=>1],
        ];

        // ======================================================
        // 🎯 SNIPERS
        // ======================================================
        $snipers = [
            ['nombre'=>'AWP | Dragon Lore','color'=>'amarillo','precio'=>5000,'foto'=>'items/armas/AWP_Dragon_Lore-Factory_new.png', 'modelo_3d'=>'modelos/csgo_awp_dragon_lore.glb', 'exterior_id'=>1],
            ['nombre'=>'AWP | Asiimov','color'=>'blanco','precio'=>400,'foto'=>'items/armas/AWP_Asiimov-Factory_new.png','exterior_id'=>1],
            ['nombre'=>'AWP | Neo-Noir','color'=>'morado','precio'=>150,'foto'=>'items/armas/AWP_Neo_Noir-Factory_new.png','exterior_id'=>1],
            ['nombre'=>'SSG 08 | Blood in the Water','color'=>'rojo','precio'=>80,'foto'=>'items/armas/SSG_08_Blood_in_the_Water_factory_new.png','exterior_id'=>1],
        ];

        // ======================================================
        // 💥 SMG
        // ======================================================
        $smg = [
            ['nombre'=>'MAC-10 | Neon Rider','color'=>'morado','precio'=>20,'foto'=>'items/armas/MAC-10_Neon_Rider_factory_new.png','exterior_id'=>1],
            ['nombre'=>'MP7 | Bloodsport','color'=>'rojo','precio'=>35,'foto'=>'items/armas/MP7_Bloodsport_factory_new.png','exterior_id'=>1],
            ['nombre'=>'P90 | Death by Kitty','color'=>'morado','precio'=>70,'foto'=>'items/armas/P90_Death_by_Kitty_factory_new.png','exterior_id'=>1],
            ['nombre'=>'UMP-45 | Primal Saber','color'=>'amarillo','precio'=>25,'foto'=>'items/armas/UMP-45_Primal_Saber_factory_new.png','exterior_id'=>1],
            ['nombre'=>'MP7 | skulls','color'=>'negro','precio'=>40,'foto'=>'items/armas/mp7__skulls_factory_new.png','modelo_3d'=>'modelos/mp7__skulls_perfect_world_edition.glb','exterior_id'=>1],

        ];

        // ======================================================
        // 💣 HEAVY
        // ======================================================
        $heavy = [
            ['nombre'=>'M249 | Nebula Crusader','color'=>'azul','precio'=>30,'foto'=>'items/armas/M249_Nebula_Crusader_factory_new.png','exterior_id'=>1],
            ['nombre'=>'Negev | Power Loader','color'=>'amarillo','precio'=>20,'foto'=>'items/armas/Negev_Power_Loader_factory_new.png','exterior_id'=>1],
        ];

        // ======================================================
        // 🧤 GLOVES (GUANTES)
        // ======================================================
        $guantes = [
            ['nombre'=>'Sport Gloves | Pandora’s Box','color'=>'morado','precio'=>8000,'foto'=>'items/guantes/Gloves_Pandora’s_Box_factory_new.png','exterior_id'=>1],
            ['nombre'=>'Driver Gloves | Crimson Weave','color'=>'rojo','precio'=>1500,'foto'=>'items/guantes/Gloves_Crimson_Weave_factory_new.png','exterior_id'=>1],
            ['nombre'=>'Hand Wraps | Slaughter','color'=>'rojo','precio'=>1200,'foto'=>'items/guantes/Gloves_Slaughter_factory_new.png','exterior_id'=>1],
            ['nombre'=>'Moto Gloves | Spearmint','color'=>'verde','precio'=>2000,'foto'=>'items/guantes/Gloves_Spearmint_factory_new.png','exterior_id'=>1],
            ['nombre'=>'Specialist Gloves | Fade','color'=>'morado','precio'=>5000,'foto'=>'items/guantes/Gloves_Fade_factory_new.png','exterior_id'=>1],
        ];

        $cuchillos = [
            ['nombre'=>'Karambit | Doppler','color'=>'morado','precio'=>2500,'foto'=>'items/cuchillos/Karambit_Doppler_Factory_New.png','exterior_id'=>1],
            ['nombre'=>'Karambit | Fade','color'=>'morado','precio'=>3000,'foto'=>'items/cuchillos/Karambit_Fade_Factory_New.png', 'modelo_3d'=>'modelos/karambit_fade.glb','exterior_id'=>1],
            ['nombre'=>'M9 Bayonet | Crimson Web','color'=>'rojo','precio'=>1800,'foto'=>'items/cuchillos/M9_Bayonet_Crimson_Web_Factory_New.png','exterior_id'=>1],
            ['nombre'=>'Bayonet | Marble Fade','color'=>'amarillo','precio'=>1200,'foto'=>'items/cuchillos/Bayonet_Marble_Fade_Factory_New.png','exterior_id'=>1],
            ['nombre'=>'Butterfly Knife | Slaughter','color'=>'rojo','precio'=>3500,'foto'=>'items/cuchillos/Butterfly_Knife_Slaughter_Factory_New.png','exterior_id'=>1],
            ['nombre'=>'Shadow Daggers | Ultraviolet','color'=>'morado','precio'=>800,'foto'=>'items/cuchillos/Shadow_Daggers_Ultraviolet_Factory_New.png','exterior_id'=>1],
            ['nombre'=>'Flip Knife | Tiger Tooth','color'=>'amarillo','precio'=>950,'foto'=>'items/cuchillos/Flip_Knife_Tiger_Tooth_Factory_New.png','exterior_id'=>1],
            ['nombre'=>'Survival Knife | Doppler','color'=>'rojo','precio'=>600,'foto'=>'items/cuchillos/Survival_Knife_Doppler_Factory_New.png','exterior_id'=>1],
        ];
        
        // ======================================================
        // 🔑 LLAVEROS (CHARMS)sadsadasd
        // ======================================================
        $llaveros = [
            ['nombre'=>'AK-47 | Bullet Charm','color'=>'negro','precio'=>25,'foto'=>'items/llaveros/AK-47_Bullet_Charm.png','exterior_id'=>1],
            ['nombre'=>'AWP | Scope Keychain','color'=>'verde','precio'=>40,'foto'=>'items/llaveros/AWP_Scope_Keychain.png','exterior_id'=>1],
            ['nombre'=>'That\'s Bananas','color'=>'amarillo','precio'=>30,'foto'=>'items/llaveros/Thats_Bananas.png','exterior_id'=>1],
            ['nombre'=>'Semi-Precious','color'=>'azul','precio'=>15,'foto'=>'items/llaveros/Semi_Precious.png','exterior_id'=>1],
            ['nombre'=>'Lil\' Chirp','color'=>'rojo','precio'=>60,'foto'=>'items/llaveros/Lil_Chirp.png','exterior_id'=>1],
        ];

        $agentes = [
            ['nombre'=>'Lt. Commander Ricksaw | NSWC SEAL','color'=>'verde','precio'=>25,'foto'=>'items/agentes/Commander_Ricksaw.png','exterior_id'=>1],
            ['nombre'=>'Seal Team 6 Soldier | NSWC SEAL','color'=>'negro','precio'=>30,'foto'=>'items/agentes/Seal_Team_6_Soldier.png','exterior_id'=>1],
            ['nombre'=>'Sabre Agent | The Elite Crew','color'=>'rojo','precio'=>45,'foto'=>'items/agentes/Sabre.png','exterior_id'=>1],
            ['nombre'=>'Number K | The Professionals','color'=>'negro','precio'=>120,'foto'=>'items/agentes/Number_K.png','exterior_id'=>1],
            ['nombre'=>'Special Agent Ava | FBI','color'=>'azul','precio'=>80,'foto'=>'items/agentes/Special_Agent_Ava.png','exterior_id'=>1],
            ['nombre'=>'Michael Syfers | FBI Sniper','color'=>'negro','precio'=>60,'foto'=>'items/agentes/Michael_Syfers.png','exterior_id'=>1],
            ['nombre'=>'Bloody Darryl The Strapped | The Professionals','color'=>'blanco','precio'=>90,'foto'=>'items/agentes/Bloody_Darryl.png','exterior_id'=>1],
        ];

        // ======================================================
        // FUNCIÓN CREAR
        // ======================================================
        $createItems = function ($items, $categoriaNombre, $tipo) use (
            $getCat, $getColor, $getCalidad, $exteriores
        ) {
            foreach ($items as $i) {

                $item = Item::create([
                    'nombre' => $i['nombre'],
                    'precio' => $i['precio'],
                    'foto' => $i['foto'],
                    'modelo_3d'    => $i['modelo_3d'] ?? null,
                    'tipo' => $tipo,
                    'calidad_id' => $getCalidad('Covert') ?? 1,
                    'categoria_id' => $getCat($categoriaNombre),
                    'exterior_id' => $i['exterior_id'],
                    'color_id' => $getColor($i['color']),
                ]);

                // SOLO armas tienen tabla armas
                if ($tipo === 'arma') {
                    Arma::create([
                        'item_id' => $item->id,
                        'categoria_id' => $getCat($categoriaNombre),
                        'exterior_id' => $item->exterior_id,
                    ]);
                }
            }
        };

        // ======================================================
        // EJECUCIÓN
        // ======================================================
        $createItems($pistolas, 'Pistolas', 'arma'); // (puedes cambiarlo a Pistolas si lo añades)
        $createItems($rifles, 'Rifles', 'arma');
        $createItems($snipers, 'Francotiradores', 'arma');
        $createItems($smg, 'Subfusiles', 'arma');
        $createItems($heavy, 'Rifles', 'arma'); // ajusta si creas Heavy
        $createItems($guantes, 'Guantes', 'guantes');
        $createItems($cuchillos, 'Cuchillos', 'cuchillo');
        $createItems($llaveros, 'Llaveros', 'llavero');
        $createItems($agentes, 'Agentes', 'agente');
        $this->command->info("🔥 Seeder CS2 estilo real ejecutado correctamente");
    }
}