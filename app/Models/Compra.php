<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Compra extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->belongsToMany(Product::class, 'compras_productos', 'compra_id', 'producto_id')
            ->withPivot('producto_cantidad', 'producto_precio_unitario');
    }
}
