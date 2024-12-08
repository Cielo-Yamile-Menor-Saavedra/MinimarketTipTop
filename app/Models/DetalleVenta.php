<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;
    protected $table='venta_detalle';
    protected $primaryKey='id';
    protected $fillable=['id_venta','id_producto','cantidad','preciopoducto','preciocompraproducto'];
    public $timestamps=false;

    public function ventas()
    {
        return $this->hasOne(Venta::class,'id','id_venta');
    }

    public function productos()
    {
        return $this->hasOne(Producto::class,'id','id_producto');
    }

    
}
