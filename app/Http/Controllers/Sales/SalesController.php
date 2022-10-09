<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Ventas\CreateVentaRequest;
use App\Repositories\Products\ProductRepository;
use Src\ProductosVentas\Repository\ProductosVentasRepository;
use Illuminate\Support\Facades\DB;
use Src\Products\Exceptions\CannotDiscountQuantity;
use App\Repositories\Sales\SaleRepository;
use App\Services\Sales\SalesService;
use Exception;

class SalesController extends ApiController
{
    protected $sales_service;

    public function __construct(SalesService $sales_service)
    {
        $this->sales_service = $sales_service;
    }

    public function index()
    {
        return response('Hello World', 200);
    }

    public function get_ventas(Request $request)
    {
        $filters =   $request->input('filters');
        $offset =   $request->input('page');
        $limit  =   json_decode($request->input('items'));

        $response = $this->sales_service->get_sales($offset, $limit, $filters);

        return $response;
    }

    public function show($id)
    {
        try {
            return $this->showAll('Se ha encontrado el producto', $this->sales_service->get_sale($id));
        } catch (\Throwable $th) {
            return $this->errorResponse(
                'Ha ocurrido un error al encontrar el producto',
                400,
                $th->getMessage() . " " . $th->getFile() . " " . $th->getLine()
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $observations        =  $request->input('observations') == 'null'? NULL : $request->input('observations');
            $sale_amount        =   $request->input('subtotal');
            $tasa_dolar_id      =   $request->input('tasa_dolar_id');
            $customer_id         =   $request->input('customerId');
            $seller_id         =   $request->input('sellerId');
            $payment_method_id         =   $request->input('paymentMethodId');
            $products           =   json_decode($request->input('products'));
            $newSale = $this->sales_service->newSale($observations, $sale_amount, $customer_id, $seller_id, $payment_method_id, $tasa_dolar_id, $products);

            return $this->showMessage("se ha realizado la venta");
        } catch (\Throwable $th) {
            // if ($th instanceof CannotDiscountQuantity) {
            //     return $this->errorResponse('Ha ocurrido un error al actualizar el producto', 400, $th->getData());
            // }

            return $this->errorResponse(
                'Ha ocurrido un error al actualizar el producto',
                400,
                $th->getMessage() . " " . $th->getFile() . " " . $th->getLine()
            );
        }
    }

    public function delete(Request $request)
    {
        // Validate the request...
        // $anulacion = $this->sales_service->anular_factura($request);

        // return $anulacion;
    }

    public function new_sale_get_data()
    {
        try {
            return $this->showAll('Se ha encontrado el producto', $this->sales_service->new_sale_get_data());
        } catch (\Throwable $th) {
            return $this->errorResponse(
                'Ha ocurrido un error al encontrar el producto',
                400,
                $th->getMessage() . " " . $th->getFile() . " " . $th->getLine()
            );
        }
    }
}
