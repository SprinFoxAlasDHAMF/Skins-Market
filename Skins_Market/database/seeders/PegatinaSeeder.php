<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PegatinaSeeder extends Seeder
{
    public function run(): void
    {
        $modos = DB::table('modo_pegatina')->get();

        $getModo = fn($nombre) => $modos->firstWhere('nombre', $nombre)?->id;

        $pegatinas = [
            ['nombre'=>'Titan (Holo)','modo'=>'Holográfica','precio'=>3000,'imagen'=>'pegatinas/TitanHolo.png'],
            ['nombre'=>'iBUYPOWER (Holo)','modo'=>'Holográfica','precio'=>2800,'imagen'=>'pegatinas/iBUYPOWER_Holo.png'],
            ['nombre'=>'Virtus.Pro (Holo)','modo'=>'Holográfica','precio'=>1500,'imagen'=>'pegatinas/Virtus_Pro_Holo.png'],
            ['nombre'=>'Ninjas in Pyjamas (Holo)','modo'=>'Holográfica','precio'=>1200,'imagen'=>'pegatinas/Ninjas_in_Pyjamas_Holo.png'],
            ['nombre'=>'Fnatic (Holo)','modo'=>'Holográfica','precio'=>1100,'imagen'=>'pegatinas/Fnatic_Holo.png'],
            ['nombre'=>'Team Dignitas (Holo)','modo'=>'Holográfica','precio'=>900,'imagen'=>'pegatinas/Dignitas_Holo.png'],
            ['nombre'=>'Cloud9 (Holo)','modo'=>'Holográfica','precio'=>700,'imagen'=>'pegatinas/Cloud9_holo.png'],
            ['nombre'=>'Astralis (Gold)','modo'=>'Gold','precio'=>600,'imagen'=>'pegatinas/Astralis_Gold.png'],
            ['nombre'=>'FaZe Clan (Holo)','modo'=>'Holográfica','precio'=>650,'imagen'=>'pegatinas/FaZe_Clan_Holo.png'],
            ['nombre'=>'G2 Esports (Holo)','modo'=>'Holográfica','precio'=>500,'imagen'=>'pegatinas/G2_Esports_Holo.png'],
            ['nombre'=>'Liquid (Holo)','modo'=>'Holográfica','precio'=>450,'imagen'=>'pegatinas/Liquid_Holo.png'],
            ['nombre'=>'Sticker | Skull (Foil)','modo'=>'Foil','precio'=>50,'imagen'=>'pegatinas/Sticker_Skull_Foil.png'],
            ['nombre'=>'Sticker | Dragon Lore','modo'=>'Normal','precio'=>200,'imagen'=>'pegatinas/Sticker_Dragon_Lore.png'],
        ];

        foreach ($pegatinas as $p) {

            $modo_id = $getModo($p['modo']);

            if (!$modo_id) {
                continue;
            }

            DB::table('pegatina')->insert([
                'nombre' => $p['nombre'],
                'modo_pegatina_id' => $modo_id,
                'precio' => $p['precio'],
                'imagen' => $p['imagen'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info("Pegatinas creadas correctamente");
    }
}