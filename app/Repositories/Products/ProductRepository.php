<?php

namespace App\Repositories\Products;

use App\DTOs\Products\ProductDTO;
use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ProductRepository
{
    protected $model;
    const PRODUCTO_ACTIVO = 1;
    const PRODUCTO_INHABILITADO = 0;
    const PRODUCTO_ELIMINADO = 2;

    public function all()
    {
        return $this->model->all();
    }
    
    public function searchProducts(string $search)
    {
        return Product::where('nombre', 'like', '%' . $search . '%')
            ->orWhere('codigo', 'like', '%' . $search . '%')
            ->where('status', 1)->get();
    }

    public function get_active_products_by_search($search)
    {
        return Product::where('codigo', 'like', '%' . $search . '%')
        ->OrWhere('nombre', 'like', '%' . $search . '%')->where('status', 1)->orderByDesc('id')->paginate(10);
    }

    public function searchByFiltersProducts($filters, $items, $page)
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
            $query = Product::where($filtrosSimples);
        } else {
            $query = Product::where('status', '=', 1);
        }

        return  $query->orderByDesc('id')->paginate(10);
    }

    public function get_products_in_min_stock()
    {
        return DB::table('productos')
            ->whereColumn('cantidad', '<=', 'cantidad_minima')
            ->get();
        //$this->model->where('cantidad','<=','cantidad_minima')->get();
    }

    public function get_products_by_offset_and_limit($offset, $limit)
    {
        return Product::skip($offset)->take($limit)->orderBy('id', 'desc')->get();
    }

    public function get_count()
    {
        return Product::get()->count();
    }

    public function create(
        $product_name,
        $product_quantity,
        $product_price_cost,
        $product_price_sale,
        $product_min_quantity,
        $product_percentage_profit,
        $status
    ) {
        $product = new Product;
        $product->nombre                =   $product_name;
        $product->cantidad              =   $product_quantity;
        $product->precio_costo          =   $product_price_cost;
        $product->precio_venta          =   $product_price_sale;
        $product->cantidad_minima       =   $product_min_quantity;
        $product->porcentaje_ganancia   =   $product_percentage_profit;
        $product->status = $status;
        $product->save();

        return $product;
    }

    public function update(Product $product)
    {
        if (!$product->isDirty()) {
            throw new Exception('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $product->save();
        return $product;
    }

    public function update_batch($products)
    {
        foreach ($products as $key => $product) {
            DB::table("productos")
                ->where('id', $product["id"])
                ->update($product);
        }

        return true;
    }

    public function update_cantidad_disponible($products)
    {
        foreach ($products as $key => $product) {
            DB::table("products")
                ->where('id', $product->id)
                ->increment('cantidad', $product->cantidad_comprada);
        }
    }

    public function change_status($status, $id)
    {
        return $this->model->where('id', $id)
            ->update($status);
    }

    public function find_product($id)
    {
        if (null == $product = Product::find($id)) {
            throw new ModelNotFoundException("Product not found");
        }

        return $product;
    }

    public function find_products_by_id($products)
    {
        $ids = array_map(fn ($product) => $product->id, $products);
        $products = Product::whereIn('id', $ids)->get();
        return $products;
    }

    public function discount_products_from_stock($products)
    {
        $query = $this->update_batch($products);

        return $query;
    }
}
