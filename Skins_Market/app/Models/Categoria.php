<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    // Tabla relacionada con el modelo
    protected $table = 'categoria'; // Asegúrate de que el nombre de la tabla sea correcto

    // Relación con la tabla Item
    public function items()
    {
        return $this->hasMany(Item::class, 'categoria_id');
    }

    // Relación con Arma
    public function armas()
    {
        return $this->hasMany(Arma::class, 'categoria_id');
    }
}

