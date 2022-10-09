<?php

namespace App\Repositories\Customers;

use App\DTOs\Customers\CustomerDTO;
use App\Models\Customer;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class CustomersRepository
{
    protected $model;
    const PRODUCTO_ACTIVO = 1;
    const PRODUCTO_INHABILITADO = 0;
    const PRODUCTO_ELIMINADO = 2;

    public function all()
    {
        return Customer::all();
    }

    public function get_active_customers_by_search($search)
    {
        $query = Customer::where('codigo', 'like', '%' . $search . '%')
            ->OrWhere('nombre', 'like', '%' . $search . '%')->where('status', 1);

        return $query->orderByDesc('id')->paginate(10);
    }

    public function searchByFilters($filters, $items, $page)
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
            $query = Customer::where($filtrosSimples);
        } else {
            $query = Customer::where('status', '=', 1);
        }

        return  $query->orderByDesc('id')->paginate(10);
    }

    public function get_customer_data($id)
    {
        $customer = Customer::where('id',"=",$id)->with(['sales'])->get();

        return $customer;
    }

    public function create(
        $name,
        $document_type,
        $document,
        $phone,
        $address
    ) : Customer {
        $customer = new Customer;
        $customer->nombre           =   $name;
        $customer->codigo           =   "";
        $customer->tipo_documento   =   $document_type;
        $customer->documento        =   $document;
        $customer->telefono         =   $phone;
        $customer->direccion        =   $address;
        $customer->status           = SELF::PRODUCTO_ACTIVO;
        $customer->save();

        return $customer;
    }

    public function update(Customer $customer) : Customer
    {
        if (!$customer->isDirty()) {
            throw new Exception('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $customer->save();
        return $customer;
    }

    public function change_status($status, $id)
    {
        return $this->model->where('id', $id)
            ->update($status);
    }

    public function find_product($id)
    {
        if (null == $product = Customer::find($id)) {
            throw new ModelNotFoundException("Customer not found");
        }

        return $product;
    }

    public function find_products_by_id($products)
    {
        $ids = array_map(fn ($product) => $product->product_id, $products);
        $products = Customer::whereIn('id', $ids)->get();
        return $products;
    }
}
