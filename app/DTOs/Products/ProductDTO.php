<?php

namespace App\DTOs\Products;

// use Spatie\DataTransferObject\DataTransferObject;

class ProductDTO
{
    public  $product_name;
    public  $product_quantity;
    public  $product_price_cost;
    public  $product_price_sale;
    public  $product_min_quantity;
    public  $status;

    public function __construct(
        $product_name,
        $product_quantity,
        $product_price_cost,
        $product_price_sale,
        $product_min_quantity,
        $product_percentage_profit,
        $status
    ) {

        $this->product_name                 =   $product_name;
        $this->product_quantity             =   $product_quantity;
        $this->product_price_cost           =   $product_price_cost;
        $this->product_price_sale           =   $product_price_sale;
        $this->product_min_quantity         =   $product_min_quantity;
        $this->product_percentage_profit    =   $product_percentage_profit;
        $this->status                       =   $status;
    }

    public function toArray()
    {
        return [
            'nombre'                =>  $this->product_name,
            'cantidad'              =>  $this->product_quantity,
            'precio_costo'          =>  $this->product_price_cos,
            'precio_venta'          =>  $this->product_price_sale,
            'cantidad_minina'       =>  $this->product_min_quantity,
            'porcentaje_ganancia'   =>  $this->product_percentage_profit,
            'status'                =>  $this->status,
        ];
    }
}
