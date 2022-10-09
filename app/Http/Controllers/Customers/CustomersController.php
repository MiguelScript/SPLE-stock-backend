<?php

namespace App\Http\Controllers\Customers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Services\Customers\CustomersService;

class CustomersController extends ApiController
{
    protected $customers_service;

    public function __construct(CustomersService $customers_service)
    {
        $this->customers_service = $customers_service;
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

        $customers = $this->customers_service->getCustomers($search, $offset, $limit, $filters);

        return $this->showAll(
            'Se han encontrado clientes',
            $customers,
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
        $this->customers_service->createCustomer(
            $request->input('nombre'),
            $request->input('tipo_documento'),
            $request->input('documento'),
            $request->input('telefono'),
            $request->input('direccion')
        );

        return $this->showMessage('Se ha creado el cliente correctamente', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get_customer_data($id)
    {
        $customer = $this->customers_service->getCustomerData($id);

        return $this->showOne(
            'Se han encontrado clientes',
            $customer
        );
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

            $this->customers_service->updateCustomer(
                $id,
                $request->input('nombre'),
                $request->input('tipo_documento'),
                $request->input('documento'),
                $request->input('telefono'),
                $request->input('direccion')
            );

            return $this->showMessage('Se ha actualizado el cliente correctamente', 200);
        } catch (\Throwable $th) {

            if ($th->getCode() == 422) {
                return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 400);
            }

            return $this->errorResponse('error', 400, $th->getMessage());
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

            $this->customers_service->changeStatusCustomer(
                $id,
                $request->input('status')
            );

            return $this->showMessage('Se ha actualizado el cliente correctamente', 200);
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
