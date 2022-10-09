<?php

namespace App\Repositories\Sellers;

use App\Models\Seller;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class SellersRepository
{
    const VENTA_ANULADA     = 0;
    const VENTA_FINALIZADA  = 1;
    const VENTA_EN_PROCESO  = 2;
    const TABLE  = 'ventas';

    public function all()
    {
        return Seller::all();
    }

    public function getActive()
    {
        return Seller::Where('status', 1)->get();
    }
}
