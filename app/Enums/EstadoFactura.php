<?php
namespace App\Enums;

enum EstadoFactura : string
{
    case PROCESO = 'Proceso';
    case FINALIZADA = 'Finalizada';
    case ANULADA = 'Anulada';

    public function toString(): string
    {
        return match ($this) {
            self::PROCESO=> 'Proceso',
            self::FINALIZADA=> 'Finalizada',
            self ::ANULADA=> 'Anulada'
        };

    }

}