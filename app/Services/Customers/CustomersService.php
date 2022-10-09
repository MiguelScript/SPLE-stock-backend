<?php

namespace App\Services\Customers;

use App\Repositories\Customers\CustomersRepository;
use App\DTOs\Customers\CustomerDTO;
use App\Models\Customer;
use Exception;

class CustomersService
{
    protected $repository;

    public function __construct(CustomersRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->all();
    }

    public function getCustomers(
        $search,
        $page,
        $items,
        $filters
    ) {
        if ($search !== null) {
            return $this->repository->get_active_customers_by_search($search);
        } else {
            return $this->repository->searchByFilters($filters, $items, $page);
        }
    }

    public function getCustomerData(
        $id
    ) {
        $customer = $this->repository->get_customer_data($id);

        if(count($customer) == 0){
            throw new Exception("Cliente no encontrado", 1);          
        }
        return $customer[0]; 
    }

    public function createCustomer(
        $name,
        $document_type,
        $document,
        $phone,
        $address
    ) {
        $product = $this->repository->create(
            $name,
            $document_type,
            $document,
            $phone,
            $address
        );

        $this->CreateCustomerCode($product);

        return $product;
    }

    public function CreateCustomerCode(Customer $customer)
    {
        $customer->codigo = "CLI" . $customer->id;
        $this->repository->update($customer);
    }

    public function updateCustomer(
        $customer_id,
        $name,
        $document_type,
        $document,
        $phone,
        $address
    ) {

        $customer = $this->repository->find_product($customer_id);

        $customer->nombre           =   $name;
        $customer->tipo_documento   =   $document_type;
        $customer->documento        =   $document;
        $customer->telefono         =   $phone;
        $customer->direccion        =   $address;

        $query = $this->repository->update($customer);

        return $query;
    }

    public function changeStatusCustomer(
        $product_id,
        $status
    ) {
        $product = Customer::find_product($product_id);
        $product->status =  $status;

        switch ($status) {
            case CustomersRepository::PRODUCTO_ACTIVO:

                return $this->activateCustomer($product);

                break;
            case CustomersRepository::PRODUCTO_INHABILITADO:

                return $this->inactivateCustomer($product);

                break;
            case CustomersRepository::PRODUCTO_ELIMINADO:

                return $this->deleteCustomer($product);

                break;
            default:
                throw new Exception("Error Processing Status", 1);
                break;
        }
    }

    public function activateCustomer($product)
    {
        return $this->repository->update(
            $product
        );
    }

    public function inactivateCustomer($product)
    {
        return $this->repository->update(
            $product
        );
    }

    public function deleteCustomer($product)
    {
        return $this->repository->update(
            $product
        );
    }
}
