<?php

namespace App\Repositories\Compras;

use App\Models\Compra;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ComprasRepository
{
    const VENTA_ANULADA     = 0;
    const VENTA_FINALIZADA  = 1;
    const VENTA_EN_PROCESO  = 2;

    public function all()
    {
        return Compra::all();
    }


    public function get_last_sales($take)
    {
        return Compra::take($take)
            ->orderBy('id', 'desc')
            ->get();
    }


    public function get_active_products_by_search($search)
    {
        $query = Compra::where('codigo', 'like', '%' . $search . '%')
            ->OrWhere('nombre', 'like', '%' . $search . '%')->where('status', 1);

        $query->with('products');

        return $query->orderByDesc('id')->paginate(10);
    }

    public function searchByFiltersProducts($filters, $items, $page)
    {
        $getFiltrosSimples = function ($filters) {
            $filtros2  = [];

            if ($filters->code != null) {
                $filtros2[] = ['codigo', 'like', '%' . $filters->code . '%'];
            }

            // if ($filters->customer != null) {
            //     $filtros2[] = ['name', 'like', '%' . $filters->name . '%'];
            // }

            $filtros2[] = ['status', '=', !$filters->inactive];

            return $filtros2;
        };


        if (!is_null($filters)) {
            $filtrosSimples = $getFiltrosSimples($filters);
            $query = Compra::where($filtrosSimples);
        } else {
            $query = Compra::where('status', '=', 1);
        }

        $query->with('products');

        return  $query->orderByDesc('id')->paginate(10);
    }

    public function get_count_by_status_and_search($status, $search)
    {
        return Compra::where('status', '=', $status)
            ->where('codigo', 'like', '%' . $search . '%')
            ->get()->count();
    }

    public function create($subtotal): Compra
    {
        $compra = new Compra;

        $compra->codigo =  "";
        $compra->subtotal =  $subtotal;
        $compra->status =  1;
        $compra->save();

        return $compra;
    }

    public function update(Compra $compra)
    {
        if (!$compra->isDirty()) {
            throw new Exception('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $compra->save();
        return $compra;
    }

    public function change_status($status, $id)
    {
        return Compra::where('id', $id)
            ->update($status);
    }

    public function delete($id)
    {
        return Compra::destroy($id);
    }

    public function find($id)
    {
        if (null == $product = Compra::find($id)) {
            throw new ModelNotFoundException("Product not found");
        }

        return $product;
    }

    public function get_products_from_sale($sale_id)
    {
        return Compra::select(
            'productos.id as producto_id',
            'productos.nombre as producto_nombre',
            'productos_ventas.producto_cantidad as producto_cantidad_vendida',
            'productos_ventas.producto_precio_unitario as producto_precio_unitario',
            'productos.cantidad as producto_cantidad_inventario',
        )
            ->join('productos_ventas', 'productos_ventas.venta_id', '=', 'ventas.id')
            ->join('productos', 'productos_ventas.producto_id', "=", 'productos.id')
            ->where('ventas.id', '=', $sale_id)
            ->get();
    }

    public function get_cantidad_productos_vendidos_and_monto_total_by_and_moth($month)
    {
        $query = DB::table('ventas')
            ->join('productos_ventas', 'productos_ventas.venta_id', '=', 'ventas.id')
            ->join('productos', 'productos_ventas.producto_id', "=", 'productos.id')
            ->selectRaw('SUM(productos_ventas.producto_cantidad) as cantidad_productos_vendidos,SUM(productos_ventas.producto_precio_unitario) as monto_total_ventas')
            //->where('pedidos.status =', 4);
            ->whereRaw('MONTH(ventas.created_at)', $month)->first();
        return $query;
    }

    // Productos compras

    public function insert_products_to_compra($productos)
    {
        return $this->model->insert($productos);
    }
}
