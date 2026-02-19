<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'item';
    protected $fillable = ['nombre', 'color', 'precio', 'foto', 'calidad_id', 'tipo'];

    // Relación con calidad
    public function calidad()
    {
        return $this->belongsTo(Calidad::class, 'calidad_id', 'id');
    }

    // Relación con armas (variantes de este item)
    public function armas()
    {
        return $this->hasMany(Arma::class, 'item_id', 'id');
    }
}
