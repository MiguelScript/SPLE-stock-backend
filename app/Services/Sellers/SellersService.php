<?php

namespace App\Services\Sellers;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Repositories\Sellers\SellersRepository;
use stdClass;

class SellersService
{
    protected $repository;

    public function __construct(SellersRepository $repository)
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
