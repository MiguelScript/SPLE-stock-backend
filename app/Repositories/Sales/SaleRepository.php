<?php

namespace App\Repositories\Sales;

use App\Models\Sale;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class SaleRepository
{
    const VENTA_ANULADA     = 0;
    const VENTA_FINALIZADA  = 1;
    const VENTA_EN_PROCESO  = 2;
    const TABLE  = 'ventas';

    public function all()
    {
        return Sale::all();
    }


    public function get_last_sales($take)
    {
        return Sale::take($take)
            ->orderBy('id', 'desc')
            ->get();
    }


    public function get_sales($filters, $items, $page)
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
            $query = Sale::where($filtrosSimples);
        } else {
            $query = Sale::where('status', '=', 1);
        }

        $query->with(['products','seller','customer','paymentMethod']);
        return  $query->orderByDesc('id')->paginate(10);
    }

    public function get_count()
    {
        return Sale::get()->count();
    }

    public function create($observations, $sale_amount, $customer_id,  $seller_id, $payment_method_id, $tasa_dolar_id, $status = 1): Sale
    {
        $sale = new Sale;

        $sale->codigo           =  "";
        $sale->observaciones    =  $observations;
        $sale->monto_venta      =  $sale_amount;
        $sale->tasa_dolar_id    =  $tasa_dolar_id;
        $sale->cliente_id       =  $customer_id;
        $sale->vendedor_id      =  $seller_id;
        $sale->metodo_pago_id   =  $payment_method_id;
        $sale->status           =  $status;
        $sale->save();

        return $sale;
    }

    public function update(Sale $sale)
    {
        return $sale->save();
    }

    public function change_status($status, $id)
    {
        return Sale::where('id', $id)
            ->update($status);
    }

    public function delete($id)
    {
        return Sale::destroy($id);
    }

    public function find($id)
    {
        if (null == $product = Sale::find($id)) {
            throw new ModelNotFoundException("Product not found");
        }

        return $product;
    }

    public function find_with_products($id)
    {
        $sale = Sale::where('id',"=",$id)->with(['products','seller','customer','paymentMethod'])->get();
        
        if(count($sale) == 0){
            throw new Exception("Venta no encontrada", 1);          
        }
        return $sale[0]; 
    }

    public function get_products_from_sale($sale_id)
    {
        return Sale::select(
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
}
