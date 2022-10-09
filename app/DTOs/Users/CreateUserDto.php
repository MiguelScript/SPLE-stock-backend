<?php

namespace App\Services\Dto\Users;

use Spatie\DataTransferObject\DataTransferObject;


class CreateUserDto  
{
    public string $nombre;
    
    public string $apellido;
    
    public  $author;

    public function fromRequest($request)
    {
        return [
            'nombre' => $request->get('nombre'),
            'apellido' => $request->get('apellido'),
            'correo' => $request->get('correo'),
            'contraseña' => $request->get('contraseña'),
        ];
    }
}
