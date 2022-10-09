<?php

namespace App\Models;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    const VENTA_ANULADA     = 0;
    const VENTA_FINALIZADA  = 1;
    const VENTA_EN_PROCESO  = 2;

    use HasFactory;

    protected $table = 'vendedores';

    public function sales()
    {
        return $this->hasMany(Sale::class, 'vendedor_id');
    }
}
