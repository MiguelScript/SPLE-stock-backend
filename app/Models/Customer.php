<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    const CLIENTE_INHABILITADO  = 0;
    const CLIENTE_ACTIVO        = 1;
    const CLIENTE_ELIMINADO     = 2;

    protected $table = 'clientes';

    public function sales()
    {
        return $this->hasMany(Sale::class, 'cliente_id');
    }
}
