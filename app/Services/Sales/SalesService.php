<?php

namespace App\Services\Sales;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Repositories\Products\ProductRepository;
use App\Repositories\Sales\SaleRepository;
use App\Services\Customers\CustomersService;
use App\Services\PaymentMethods\PaymentMethodsService;
use App\Services\Sellers\SellersService;
use stdClass;

class SalesService
{
    protected $repository;
    protected $product_repository;
    protected $customer_service;
    protected $payment_methods_service;
    protected $sellers_service;

    public function __construct(
        SaleRepository $repository,
        CustomersService $customer_service,
        PaymentMethodsService $payment_methods_service,
        SellersService $sellers_service
    ) {
        $this->repository = $repository;
        $this->customer_service = $customer_service;
        $this->payment_methods_service = $payment_methods_service;
        $this->sellers_service = $sellers_service;
        $this->product_repository = new ProductRepository;
    }

    public function newSale($observations, $sale_amount, $customer_id, $seller_id, $payment_method_id, $tasa_dolar_id, $products_sale)
    {
        $sale = $this->createSale($observations, $sale_amount, $customer_id, $seller_id, $payment_method_id, $tasa_dolar_id);
        $products_in_stock = $this->product_repository->find_products_by_id($products_sale);
        $products_to_discount = array();
        $errores = array();

        // Crear array con solo id de productos en la compra
        $products_sale_ids =  array_column($products_sale, 'id');

        /* Crear nuevo array para actualizar las cantidades disponibles */
        foreach ($products_in_stock as  $product_in_stock) {
            // buscar producto en array de productos a comprar
            $key = array_search($product_in_stock->id, $products_sale_ids);

            if ($key === false) {
                throw new Exception("El producto no existe", 1);
            }

            $current_product = $products_sale[$key];
            $nueva_cantidad = $product_in_stock->cantidad - $current_product->quantityInInvoice;

            if ($nueva_cantidad < 0) {
                throw new Exception("No se puede descontar la cantidad de un producto", 1);
            }

            $item_sale = array(
                'id' => $product_in_stock->id,
                'cantidad' => $nueva_cantidad,
            );

            $products_to_discount[] = $item_sale;
        }

        DB::transaction(function () use ($products_sale, $sale, $products_to_discount) {
            /* Agregar productos a la tabla intermedia que almacena el detalle de la compra */

            $this->addProductsToSale($products_sale, $sale);

            $this->discountQuantityToProducts($products_to_discount);
        });

        if (count($errores) > 0) {
            // throw new CannotDiscountQuantity("No se puede descontar la cantidad de un producto", $errores);
            // return $this->errorResponse('Ha ocurrido un error al crear la venta', 400, $errores);
        }
    }

    public function CreateSale($observations, $sale_amount, $customer_id, $seller_id, $payment_method_id, $tasa_dolar_id)
    {
        $sale = $this->repository->create($observations, $sale_amount, $customer_id,  $seller_id, $payment_method_id, $tasa_dolar_id);
        $this->CreateSaleCode($sale);

        return $sale;
    }

    public function CreateSaleCode($sale)
    {
        $sale->codigo = "VEN" . $sale->id;
        $this->repository->update($sale);
    }

    public function ChangeStatus(
        $product_id,
        $status
    ) {

        switch ($status) {
            case 'incompleted':

                $status_value = SaleRepository::VENTA_EN_PROCESO;

                break;
            case 'completed':

                $status_value = SaleRepository::VENTA_FINALIZADA;

                break;
            case 'cancel':

                $status_value = SaleRepository::VENTA_ANULADA;

                break;

            default:

                throw new Exception("Error Processing Status", 1);

                break;
        }

        $status = array(
            'status' => $status_value
        );

        $query = $this->repository->change_status($status, $product_id);
        return $query;
    }

    public function GetProductsSoldBySale($sale_id)
    {
        return $this->repository->get_products_from_sale($sale_id);
    }

    // public function GetproductsFromSale($sale_id)
    // {
    //     return $this->repository->get_productos_and_cantidad_comprada_by_venta_id($sale_id);
    // }

    public function addProductsToSale(array $products, $venta)
    {
        $productsToAdd = array();

        foreach ($products as $product) {
            $productsToAdd[$product->id] =  [
                'producto_cantidad' => $product->quantityInInvoice,
                'producto_precio_unitario' => $product->precio_venta,
            ];
        }

        $venta->products()->attach($productsToAdd);
    }

    public function get_sales($page, $items, $filters)
    {
        return $this->repository->get_sales($filters, $items, $page);
    }

    public function new_sale_get_data()
    {

        return [
            'customers' => $this->customer_service->getAll(),
            'paymentMethods' => $this->payment_methods_service->getActive(),
            'sellers' => $this->sellers_service->getActive(),
        ];
    }

    public function get_sale($id)
    {
        return $this->repository->find_with_products($id);
    }

    public function get_count()
    {
        $query = $this->repository->get_count_by_status_and_search($this->status, $this->search);
        return $query;
    }

    public function discountQuantityToProducts(array $products)
    {
        $discount_products_from_stock =  $this->product_repository->discount_products_from_stock($products);

        // return $add_products;
    }
}
