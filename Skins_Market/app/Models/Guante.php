<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guante extends Model
{
    protected $table = 'guantes';

    protected $fillable = [
        'item_id',
        'categoria_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
