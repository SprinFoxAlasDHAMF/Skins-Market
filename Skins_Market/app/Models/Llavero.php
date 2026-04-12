<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Llavero extends Model
{
    protected $table = 'llaveros';

    protected $fillable = [
        'item_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}