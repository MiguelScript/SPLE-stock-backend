<?php

namespace Src\ProductosVentas\Repository;

use Src\ProductosVentas\Model\ProductosVentas;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductosVentasRepository
{
    protected $model;

    /**
     * UserRepository constructor.
     *
     * @param User $post
     */
    public function __construct()
    {
        $this->model = new ProductosVentas();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function get_productos_and_cantidad_comprada_by_venta_id($venta_id)
    {
        return $this->model->select(
            'productos_ventas.producto_id as producto_id', 
            'productos_ventas.producto_cantidad as producto_cantidad', 
            'productos.cantidad as producto_cantidad_inventario', 
            )
            ->join('productos', 'productos_ventas.producto_id', '=', 'productos.id')
            ->where('productos_ventas.venta_id', '=', $venta_id)
            ->get();
    }

    public function insert_products($productos)
    {
        return $this->model->insert($productos);
    }

    public function update(array $data, $id)
    {
        return $this->model->where('id', $id)
            ->update($data);
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function find($id)
    {
        if (null == $product = $this->model->find($id)) {
            throw new ModelNotFoundException("Product not found");
        }

        return $product;
    }
}
