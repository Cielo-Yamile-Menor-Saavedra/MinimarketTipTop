<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boleta extends Model
{
    use HasFactory;
    protected $table='boletas';
    protected $primaryKey='idboleta';
    protected $fillable=['serie','numero','venta_id','created_at','monto','pago','estadoboleta'];
    public $timestamps=false;

    public function ventas()
    {
        return $this->hasOne(Venta::class,'id','venta_id');
    }
    
}
