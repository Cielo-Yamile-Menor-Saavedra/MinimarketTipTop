<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'codigo',
        'id_categoria',
        'imagen',
        'nombre_producto',
        'descripcion_producto',
        'stock',
        'stock_minimo',
        'stock_maximo',
        'precio_compra',
        'precio_venta',
        'fecha_ingreso',
        'id_usuario', // Si es relevante
        'estadoproducto'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public static function DisminuirStockProducto($idproducto,$cantidad){
        // return DB::update(
        //     DB::raw("UPDATE productos SET stock = stock - ? WHERE id = ?"),
        //     [$cantidad, $idproducto]
        // );
        // Asegúrate de utilizar DB::update() y no DB::select()
        return DB::update("UPDATE productos SET stock = stock - ? WHERE id = ?", [$cantidad, $idproducto]);
    }
    public static function AumentarStockProducto($idproducto,$cantidad){
        return DB::update("UPDATE productos set stock = stock + ? WHERE id = ?", [$cantidad, $idproducto]);
    }

    public function compras()
    {
    return $this->hasMany(Compra::class);
    }

    public function ordens()
    {
    return $this->hasMany(Orden::class);
    }
}
