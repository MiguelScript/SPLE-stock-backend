<?php

namespace App\Http\Controllers\DollarRate;

use App\Http\Controllers\ApiController;
use App\Services\DollarRate\DollarRateService;
use Illuminate\Http\Request;

class DollarRateController extends ApiController
{
    protected $dollar_rate_service;

    public function __construct(DollarRateService $dollar_rate_service)
    {
        $this->dollar_rate_service = $dollar_rate_service;
    }

    public function get_current()
    {
        $dollarRate = $this->dollar_rate_service->get_current_dollar_rate();

        return $this->showOne(
            'Se han encontrado tasa dolar',
            $dollarRate
        );
    }

    public function store(Request $request)
    {
        $dollarRate = $this->dollar_rate_service->create(
            $request->input('rate')
        );

        return $this->showMessage('Se ha actualizado la tasa correctamente', 201);
    }
}
