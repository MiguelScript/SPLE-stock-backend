<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    const PRODUCTO_INHABILITADO = 0;
    const PRODUCTO_ACTIVO        = 1;
    const PRODUCTO_ELIMINADO    = 2;

    protected $table = 'productos';

    public function ventas()
    {
        return $this->belongsToMany(Sale::class, 'productos_ventas', 'producto_id', 'venta_id') ->withPivot('producto_cantidad');
    }

    public function compras()
    {
        return $this->belongsToMany(Compra::class, 'compras_productos', 'producto_id')
            ->withPivot('producto_cantidad', 'producto_precio_unitario');
    }
}
