<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ModoPegatina;
use App\Models\Arma;

class Pegatina extends Model
{
    use HasFactory;

    protected $table = 'pegatina'; // o 'pegatinas' según tu tabla
    protected $fillable = ['modo_pegatina_id', 'precio'];

    public function modoPegatina()
    {
        return $this->belongsTo(ModoPegatina::class, 'modo_pegatina_id', 'id');
    }

    public function armas()
    {
        return $this->belongsToMany(Arma::class, 'arma_pegatina', 'pegatina_id', 'arma_id');
    }
}
