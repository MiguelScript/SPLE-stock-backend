<?php

namespace Src\Stats\Services;

use Src\Products\Repository\ProductRepository;

class StatsService 
{
    public function get_products_in_min_stock()
    {
        $query = $this->repository->get_products_in_min_stock();
        return $query;
    }
    
    public function get_count()
    {
        $query = $this->repository->get_count();
        return $query;
    }

    public function get_last_sales()
    {
        $take = 5;
        $query = $this->repository->get_last_sales($take);
        return $query;
    }

    public function get_productos_vendidos_and_monto_total_ventas()
    {
        $take = 5;
        $query = $this->repository->get_cantidad_productos_vendidos_and_monto_total_by_and_moth($take);
        return $query;
    }
}
