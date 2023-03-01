<?php
namespace App\Enums;

enum EstadoGeneral : string
{
    case ACTIVO = 'activo';
    case INACTIVO = 'inactivo';

    public function toString(): string
    {
        return match($this)
        {
            self::ACTIVO=>'activo',
            self::INACTIVO=>'inactivo',
        };
    }


}