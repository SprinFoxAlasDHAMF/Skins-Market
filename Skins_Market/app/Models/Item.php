<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'item';
    protected $fillable = ['nombre', 'precio', 'foto','modelo_3d', 'calidad_id', 'tipo', 'categoria_id', 'exterior_id', 'color_id'];

    // Relación con calidad
    public function calidad()
    {
        return $this->belongsTo(Calidad::class, 'calidad_id', 'id');
    }

    // Relación con categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id');
    }

    // Relación con exterior
    public function exterior()
    {
        return $this->belongsTo(Exterior::class, 'exterior_id', 'id');
    }

    // Relación con color
    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }

    // Relación con armas (variantes de este item)
    public function armas()
    {
        return $this->hasMany(Arma::class, 'item_id', 'id');
    }

    public function usuariosFavoritos()
    {
        return $this->belongsToMany(User::class, 'favoritos', 'item_id', 'usuario_id')->withTimestamps();
    }
}
