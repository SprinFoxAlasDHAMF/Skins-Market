<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $table = 'factura';
    protected $fillable = ['usuario_id', 'fecha', 'total', 'metodo_pago', 'estado'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }

    public function detalles()
    {
        return $this->hasMany(FacturaDetalle::class, 'factura_id', 'id');
    }
}