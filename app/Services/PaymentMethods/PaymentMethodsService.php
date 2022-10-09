<?php

namespace App\Services\PaymentMethods;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Repositories\PaymentMethods\PaymentMethodsRepository;
use stdClass;

class PaymentMethodsService
{
    protected $repository;

    public function __construct(PaymentMethodsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->all();
    }

    public function getActive()
    {
        return $this->repository->getActive();
    }
}
