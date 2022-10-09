<?php

namespace Src\Stats\Repository;

use Src\Sales\Model\Sale;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;


class SaleRepository
{
    protected $model;
    const VENTA_ANULADA     = 0;
    const VENTA_FINALIZADA  = 1;
    const VENTA_EN_PROCESO  = 2;

    /**
     * SaleRepository constructor.
     *
     * @param Sale $post
     */
    public function __construct(Sale $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function get_last_sales($take)
    {
        return $this->model
            ->take($take)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function get_sales_by_offset_and_limit($offset, $limit)
    {
        return $this->model
            ->skip($offset)
            ->take($limit)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function get_count()
    {
        return $this->model->get()->count();
    }

    public function get_sales_by_offset_and_limit_and_search($offset, $limit, $search)
    {
        return $this->model
            ->where('codigo', 'like', '%' . $search . '%')
            ->skip($offset)
            ->take($limit)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function get_count_by_search($search)
    {
        return $this->model
            ->where('codigo', 'like', '%' . $search . '%')
            ->get()
            ->count();
    }

    public function get_sales_by_offset_and_limit_and_status($offset, $limit, $status)
    {
        return $this->model
            ->where('status', '=', $status)
            ->skip($offset)
            ->take($limit)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function get_count_by_status($status)
    {
        return $this->model->where('status', '=', $status)->get()->count();
    }

    public function get_sales_by_offset_and_limit_and_status_and_search($offset, $limit, $status, $search)
    {
        return $this->model
            ->where('codigo', 'like', '%' . $search . '%')
            ->where('status', '=', $status)
            ->skip($offset)->take($limit)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function get_count_by_status_and_search($status, $search)
    {
        return $this->model
            ->where('status', '=', $status)
            ->where('codigo', 'like', '%' . $search . '%')
            ->get()->count();
    }

    public function find($id)
    {
        if (null == $product = $this->model->find($id)) {
            throw new ModelNotFoundException("Product not found");
        }

        return $product;
    }

    public function get_products_from_sale($sale_id)
    {
        # code...
    }

    public function get_cantidad_productos_vendidos_and_monto_total_by_and_moth($month)
    {
        $query = DB::table('ventas')
        ->join('carritos_productos', 'carritos_productos.carrito_id = pedidos.carrito_compras_id')
        ->join('productos', 'carritos_productos.producto_id = productos.id')
        ->select('
            SUM(carritos_productos.producto_cantidad) as cantidad_productos_vendidos,
            SUM(carritos_productos.producto_precio) as monto_total_ventas,
            ')
        //->where('pedidos.status =', 4);
        ->where('MONTH(pedidos.created_at)', $month)->get();
        return $query;
    }

}
