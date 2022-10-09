<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Services\Products\ProductsService;

class ProductsController extends ApiController
{
    protected $products_service;

    public function __construct(ProductsService $products_service)
    {
        $this->products_service = $products_service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search =   $request->input('search');
        $limit =   $request->input('limit');
        $offset =   $request->input('page');
        $filters  =   json_decode($request->input('filters'));

        $products = $this->products_service->getProducts($search, $offset, $limit, $filters);

        return $this->showAll(
            'Se han encontrado productos',
            $products,
            200
        );
    }

    public function search(Request $request)
    {
        $products = $this->products_service->searchProducts($request->input('search'));

        return $this->showAll(
            'Se han encontrado productos',
            $products,
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->products_service->createProduct(
            $request->input('nombre'),
            $request->input('cantidad'),
            $request->input('precio_costo'),
            $request->input('precio_venta'),
            $request->input('cantidad_minima'),
            0,
            Product::PRODUCTO_ACTIVO
        );

        return $this->showMessage('Se ha creado el producto correctamente', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            $this->products_service->updateProduct(
                $id,
                $request->input('nombre'),
                $request->input('cantidad'),
                $request->input('precio_costo'),
                $request->input('precio_venta'),
                $request->input('cantidad_minima'),
                0,
                $request->input('status'),
            );

            return $this->showMessage('Se ha actualizado el producto correctamente', 200);

        } catch (\Throwable $th) {
            
            if ($th->getCode() == 422) {
                return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 400);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $id)
    {
        try {

            $this->products_service->changeStatusProduct(
                $id,
                $request->input('status')
            );

            return $this->showMessage('Se ha actualizado el producto correctamente', 200);

        } catch (\Throwable $th) {
            
            if ($th->getCode() == 422) {
                return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 400);
            }

            return $this->errorResponse($th->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
