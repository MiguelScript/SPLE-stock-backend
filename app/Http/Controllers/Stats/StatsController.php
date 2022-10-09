<?php

namespace App\Http\Controllers\Stats;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Src\Stats\Handler\GetStatsHandler;
use Illuminate\Http\Request;

class GetStatsController extends ApiController
{
    public function getStatsbyMoth(Request $request)
    {
        $month  = $request->input('month');
        $products_in_min_stock = $this->get_products_service->get_products_in_min_stock();
        $get_last_sales = $this->get_sales_service->get_last_sales();
        $products_stats = $this->get_sales_service->get_productos_vendidos_and_monto_total_ventas($month);
        //$get_last_sales = $this->get_sales_service->get_sales();
        $sales_stats = array(
            'last_sales' => $get_last_sales, 
            'stats_by_moth' => $products_stats, 
        );
        return [
            'data' => array(
                'ventas' => $sales_stats,
                'productos' => $products_stats
            ),
            'msg' => "Se ha encontrado ventas",
        ];
    }
}