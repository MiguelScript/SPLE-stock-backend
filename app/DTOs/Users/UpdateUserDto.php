<?php

namespace App\Services\Dto\Users;

use Spatie\DataTransferObject\DataTransferObject;


class UpdateUserDto  
{
    public string $nombre;
    
    public string $apellido;
    
    public  $author;

    public function fromRequest($request)
    {
        return [
            'id' => $request->get('id'),
            'nombre' => $request->get('nombre'),
            'apellido' => $request->get('apellido'),
            'correo' => $request->get('correo'),
            'contraseña' => $request->get('contraseña'),
        ];
    }
}
