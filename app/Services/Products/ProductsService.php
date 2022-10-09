<?php

namespace App\Services\Products;

use App\Repositories\Products\ProductRepository;
use App\DTOs\Products\ProductDTO;
use App\Models\Product;
use Exception;

class ProductsService
{
    protected $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getProducts(
        $search,
        $page,
        $items,
        $filters
    ) {
        if ($search !== null) {
            return $this->repository->get_active_products_by_search($search);
        } else {
            return $this->repository->searchByFiltersProducts($filters, $items, $page);
        }
    }

    public function searchProducts(string $search)
    {
        return $this->repository->searchProducts($search);
    }

    public function createProduct(
        $product_name,
        $product_quantity,
        $product_price_cost,
        $product_price_sale,
        $product_min_quantity,
        $product_percentage_profit
    ) {

        $product = $this->repository->create(
            $product_name,
            $product_quantity,
            $product_price_cost,
            $product_price_sale,
            $product_min_quantity,
            $product_percentage_profit,
            ProductRepository::PRODUCTO_ACTIVO
        );

        $this->CreateProductCode($product);

        return $product;
    }

    public function CreateProductCode(Product $product)
    {
        $product->codigo = "PRO" . $product->id;
        $this->repository->update($product);
    }

    public function UpdateMultipleProducts($products)
    {
        $query = $this->repository->update_batch($products);

        if ($query) {
            return $query;
        } else {
            throw new Exception("No se actualizo la cantidad disponible de los productos", 1);
        }
    }

    public function updateProduct(
        $product_id,
        $product_name,
        $product_quantity,
        $product_price_cost,
        $product_price_sale,
        $product_min_quantity,
        $product_percentage_profit,
        $status
    ) {

        $product = $this->repository->find_product($product_id);

        $product->nombre                =   $product_name;
        $product->cantidad              =   $product_quantity;
        $product->precio_costo          =   $product_price_cost;
        $product->precio_venta          =   $product_price_sale;
        $product->cantidad_minima       =   $product_min_quantity;
        $product->porcentaje_ganancia   =   $product_percentage_profit;
        $product->status                =   $status;
        
        $query = $this->repository->update($product);

        return $query;
    }

    public function changeStatusProduct(
        $product_id,
        $status
    ) {
        $product = Product::findOrFail($product_id);
        $product->status =  $status;

        switch ($status) {
            case ProductRepository::PRODUCTO_ACTIVO:

                return $this->activateProduct($product);

                break;
            case ProductRepository::PRODUCTO_INHABILITADO:

                return $this->inactivateProduct($product);

                break;
            case ProductRepository::PRODUCTO_ELIMINADO:

                return $this->deleteProduct($product);

                break;
            default:
                throw new Exception("Error Processing Status", 1);
                break;
        }
    }

    public function activateProduct($product)
    {
        return $this->repository->update(
            $product
        );
    }

    public function inactivateProduct($product)
    {
        return $this->repository->update(
            $product
        );
    }

    public function deleteProduct($product)
    {
        return $this->repository->update(
            $product
        );
    }
}
