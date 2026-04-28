<?php

namespace App\Services;

use App\Models\Arma;
use App\Models\Guante;
use App\Models\Agente;

class ItemTypeFactory
{
    public static function handle($item): void
    {
        match ($item->tipo) {
            'arma' => self::createArma($item),
            'guantes' => self::createGuantes($item),
            'agente' => self::createAgentes($item),
            default => null,
        };
    }

    public static function sync($item): void
    {
        match ($item->tipo) {
            'arma' => self::syncArma($item),
            'guantes' => self::syncGuantes($item),
            'agente' => self::syncAgentes($item),
            default => null,
        };
    }

    public static function delete($item): void
    {
        match ($item->tipo) {
            'arma' => Arma::where('item_id', $item->id)->delete(),
            'guantes' => Guante::where('item_id', $item->id)->delete(),
            'agente' => Agente::where('item_id', $item->id)->delete(),
            default => null,
        };
    }

    // ---------------- ARMAS ----------------

    private static function createArma($item): void
    {
        Arma::create([
            'item_id' => $item->id,
            'categoria_id' => $item->categoria_id,
            'exterior_id' => $item->exterior_id,
        ]);
    }

    private static function syncArma($item): void
    {
        Arma::updateOrCreate(
            ['item_id' => $item->id],
            [
                'categoria_id' => $item->categoria_id,
                'exterior_id' => $item->exterior_id,
            ]
        );
    }

    // ---------------- GUANTES ----------------

    private static function createGuantes($item): void
    {
        \App\Models\Guante::create([
            'item_id' => $item->id,
            'exterior_id' => $item->exterior_id,
        ]);
    }

    private static function syncGuantes($item): void
    {
        Guante::updateOrCreate(
            ['item_id' => $item->id],
            [
                'exterior_id' => $item->exterior_id ?? null,
            ]
        );
    }

    // ---------------- AGENTES ----------------

    private static function createAgentes($item): void
    {
        Agente::create([
            'item_id' => $item->id,
        ]);
    }

    private static function syncAgentes($item): void
    {
        Agente::updateOrCreate(
            ['item_id' => $item->id],
            []
        );
    }
    public static function migrateType($item): void
    {
        // 1. borrar de todos los tipos anteriores
        \App\Models\Arma::where('item_id', $item->id)->delete();
        \App\Models\Guante::where('item_id', $item->id)->delete();
        \App\Models\Agente::where('item_id', $item->id)->delete();

        // 2. crear en el nuevo tipo
        self::handle($item);
    }
}