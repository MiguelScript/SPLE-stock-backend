<?php

namespace App\Services\Compras;

use App\Repositories\Compras\ComprasRepository;
use App\Repositories\Products\ProductRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ComprasService
{
    protected $repository;
    protected $productsRepository;

    public function __construct(ComprasRepository $repository, ProductRepository $productsRepository) 
    {
        $this->repository = $repository;
        $this->productsRepository = $productsRepository;
    }

    public function get_compras($search, $page, $items, $filters)
    {
        if ($search !== null) {
            return $this->repository->get_active_products_by_search($search);
        } else {
            return $this->repository->searchByFiltersProducts($filters, $items, $page);
        }
    }

    public function get_count()
    {
        $query = $this->repository->get_count_by_status_and_search($this->status, $this->search);
        return $query;
    }

    public function newCompra($subtotal, $products_compra)
    {
        $compra = $this->createCompra($subtotal);
        $product_repository = new ProductRepository;
        $products_in_stock = $product_repository->find_products_by_id($products_compra);
        //$products_in_stock_ids = array_column($products_in_stock, 'id');

        // DB::transaction(function () use ($products_compra, $products_in_stock, $compra) {

        $products_to_increase = array();
        $errores = array();
        /* Agregar productos a la tabla intermedia que almacena el detalle de la compra */

        $this->addProductsToCompra($products_compra, $compra);

        // Crear array con solo id de productos en la compra
        $products_compra_ids =  array_column($products_compra, 'id');

        /* Crear nuevo array para actualizar las cantidades disponibles */
        foreach ($products_in_stock as  $product_in_stock) {
            // buscar producto en array de productos a comprar
            $key = array_search($product_in_stock->id, $products_compra_ids);

            if ($key === false) {
                throw new Exception("El producto no existe", 1);
            }

            $current_product = $products_compra[$key];

            // $current_product = $this->find_product_in_compra($products_in_stock->id, $products_compra);
            $nueva_cantidad = $product_in_stock->cantidad + $current_product->quantityInInvoice;

            $item_compra = array(
                'id' => $product_in_stock->id,
                'cantidad' => $nueva_cantidad,
            );

            $products_to_increase[] = $item_compra;
        }

        $this->addQuantityToProducts($products_to_increase);

        if (count($errores) > 0) {
            // throw new CannotDiscountQuantity("No se puede descontar la cantidad de un producto", $errores);
            // return $this->errorResponse('Ha ocurrido un error al crear la venta', 400, $errores);
        }
    }

    public function createCompra($subtotal)
    {
        $compra = $this->repository->create($subtotal);
        $this->CreateCompraCode($compra);

        return $compra;
    }

    public function CreateCompraCode($compra)
    {
        $compra->codigo = "COM" . $compra->id;
        $this->repository->update($compra);
    }

    public function ChangeStatus(
        $product_id,
        $status
    ) {

        switch ($status) {
            case 'incompleted':

                $status_value = ComprasRepository::VENTA_EN_PROCESO;

                break;
            case 'completed':

                $status_value = ComprasRepository::VENTA_FINALIZADA;

                break;
            case 'cancel':

                $status_value = ComprasRepository::VENTA_ANULADA;

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

    public function GetproductsFromCompra($sale_id)
    {
        // return $this->repository->get_productos_and_cantidad_comprada_by_venta_id($sale_id);
    }

    public function addProductsToCompra(array $products_compra, $compra)
    {
        // Toamr solo los detalles de los productos comprados

        $productsToAdd = [];

        foreach ($products_compra as $product) {
            $productsToAdd[$product->id] =  [
                'producto_cantidad' => $product->quantityInInvoice,
                'producto_precio_unitario' => $product->precio_costo
            ];
        }

        // agregar los products a la tabla intermedia
        $compra->products()->attach($productsToAdd);
    }

    public function addQuantityToProducts(array $products)
    {
        $add_products = $this->productsRepository->update_batch($products);
    }
}
