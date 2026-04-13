<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModoPegatina extends Model
{
    use HasFactory;

    protected $table = 'modo_pegatina';
    protected $fillable = ['nombre'];

    public function pegatinas()
    {
        return $this->hasMany(Pegatina::class, 'modo_pegatina_id', 'id');
    }
}
