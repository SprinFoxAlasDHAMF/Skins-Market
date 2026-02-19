<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calidad extends Model
{
    // Tabla relacionada con el modelo
    protected $table = 'calidad'; // Asegúrate de que el nombre de la tabla sea correcto

    // Relación con la tabla Item
    public function items()
    {
        return $this->hasMany(Item::class, 'calidad_id');
    }
}
