<?php

namespace App\Repositories\DollarRate;

use App\Models\DollarRate;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DollarRateRepository
{
    protected $model;
    const VENTA_ANULADA     = 0;
    const VENTA_FINALIZADA  = 1;
    const VENTA_EN_PROCESO  = 2;

    /**
     * DollarRateRepository constructor.
     *
     * @param DollarRate $post
     */
    public function __construct(DollarRate $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function get_current_dollar_rate()
    {
        return $this->model
            ->orderBy('id', 'desc')
            ->first();
    }

    public function create(
        $rate
    ): DollarRate {

        $dollar_rate = new DollarRate;
        $dollar_rate->tasa =   $rate;
        $dollar_rate->save();

        return $dollar_rate;
    }
}
