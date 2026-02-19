<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exterior extends Model
{
    // Tabla relacionada con el modelo
    protected $table = 'exterior'; // Asegúrate de que el nombre de la tabla sea correcto

    // Relación con la tabla Arma
    public function armas()
    {
        return $this->hasMany(Arma::class, 'exterior_id');
    }
}

