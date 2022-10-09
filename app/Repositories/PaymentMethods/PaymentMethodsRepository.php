<?php

namespace App\Repositories\PaymentMethods;

use App\Models\PaymentMethod;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class PaymentMethodsRepository
{
    const VENTA_ANULADA     = 0;
    const VENTA_FINALIZADA  = 1;
    const VENTA_EN_PROCESO  = 2;
    const TABLE  = 'ventas';

    public function all()
    {
        return PaymentMethod::all();
    }

    public function getActive()
    {
        return PaymentMethod::Where('status', 1)->get();
    }
}
