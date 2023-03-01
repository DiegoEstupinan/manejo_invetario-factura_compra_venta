<?php

namespace App\Enums;

enum Rol: string
{
    case  ADMINISTRADOR = 'administrador';
    case  EMPLEADO = 'empleado';
    case CLIENTE = 'cliente';
    case PROVEEDOR = 'proveedor';


    public function toString(): string
    {
        return match ($this) {
            self::ADMINISTRADOR => 'administrador',
            self::EMPLEADO=> 'empleado',
            self::CLIENTE => 'cliente',
            self::PROVEEDOR => 'proveedor',
        };
    }
}