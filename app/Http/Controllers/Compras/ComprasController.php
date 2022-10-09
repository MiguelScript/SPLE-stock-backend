<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Repositories\Products\ProductRepository;
use App\Services\Compras\ComprasService;
use Illuminate\Support\Facades\DB;
use Exception;

class ComprasController extends ApiController
{
    protected $compras_service;

    /**
     * UserRepository constructor.
     *
     * @param Compras $post
     */
    public function __construct(ComprasService $compraService)
    {
        $this->compras_service = $compraService;
    }

    public function index(Request $request)
    {
        $search =   $request->input('search');
        $limit =   $request->input('limit');
        $offset =   $request->input('page');
        $filters  =   json_decode($request->input('filters'));

        $products = $this->compras_service->get_compras($search, $offset, $limit, $filters);

        return $this->showAll(
            'Se han encontrado productos',
            $products,
            200
        );
    }

    public function get_compras(Request $request)
    {
        // $response = $this->compras_service->get_compras($request);

        // return $response;
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
            $subtotal   =   $request->input('subtotal');
            $products   =   json_decode($request->input('products'));
            $newCompra  =   $this->compras_service->newCompra($subtotal, $products);

            return $this->showMessage("se ha realizado la compra");
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
        // $anulacion = $this->compras_service->anular_factura($request);

        // return $anulacion;
    }
}
