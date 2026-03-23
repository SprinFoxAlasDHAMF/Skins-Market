<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaDetalle extends Model
{
    use HasFactory;

    protected $table = 'factura_detalle';
    protected $fillable = ['factura_id', 'item_id', 'cantidad', 'precio_unitario', 'subtotal'];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function factura()
    {
        return $this->belongsTo(Factura::class, 'factura_id', 'id');
    }
}