<?php

namespace App\Services\Dto\Products;

use Spatie\DataTransferObject\DataTransferObject;


class UpdateProductDto  
{
    public function fromRequest($request)
    {
        return [
            'codigo' => $request->get('codigo'),
            'nombre' => $request->get('nombre'),
            'cantidad' => $request->get('cantidad'),
            'precio_costo' => $request->get('precio_costo'),
            'porcentaje_ganancia' => $request->get('porcentaje_ganancia'),
        ];
    }
}
