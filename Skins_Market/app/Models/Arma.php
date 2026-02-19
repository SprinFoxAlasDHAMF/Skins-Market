<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pegatina;
use App\Models\ModoPegatina;
use App\Models\Item;
use App\Models\Exterior;
use App\Models\Categoria;

class Arma extends Model
{
    use HasFactory;

    protected $table = 'arma';
    protected $fillable = ['categoria_id'];
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function exterior()
    {
        return $this->belongsTo(Exterior::class, 'exterior_id', 'id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id');
    }

    public function pegatinas()
    {
        return $this->belongsToMany(Pegatina::class, 'arma_pegatina', 'arma_id', 'pegatina_id');
    }
}
