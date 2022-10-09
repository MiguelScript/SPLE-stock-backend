<?php

namespace App\Services\DollarRate;

use App\Repositories\DollarRate\DollarRateRepository;
use App\DTOs\Products\ProductDTO;
use Exception;

class DollarRateService
{
    protected $repository;

    public function __construct(DollarRateRepository $repository)
    {
        $this->repository = $repository;
    }

    public function get_current_dollar_rate()
    {
        return $this->repository->get_current_dollar_rate();
    }

    public function create(
        $rate
    ) {
        return $this->repository->create($rate);
    }
}
