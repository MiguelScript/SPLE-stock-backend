<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Customer;
use App\Models\PaymentMethod;

class Sale extends Model
{
    const VENTA_ANULADA     = 0;
    const VENTA_FINALIZADA  = 1;
    const VENTA_EN_PROCESO  = 2;

    use HasFactory;

    protected $table = 'ventas';

    public function products()
    {
        return $this->belongsToMany(Product::class, 'productos_ventas', 'venta_id', 'producto_id')
            ->withPivot('producto_cantidad', 'producto_precio_unitario');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'metodo_pago_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cliente_id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'vendedor_id');
    }
}
