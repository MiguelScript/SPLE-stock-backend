<?php

namespace App\Services\Dto\Ventas;

use Spatie\DataTransferObject\DataTransferObject;


class CreateVentaDto  
{
    public function fromRequest($request)
    {
        return [
            'productos' => $request->get('productos'),
            'total' => $request->get('subtotal'),
        ];
    }
}
