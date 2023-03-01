<?php
namespace App\Enums;

enum TipoDocumento : string
{
    case  CEDULA = 'Cedula';
    case NIT = 'Nit';


    public function toString(): string
    {
        return match ($this) {
            self::CEDULA => 'Cedula',
            self::NIT => 'Nit',
        };
    }
}

