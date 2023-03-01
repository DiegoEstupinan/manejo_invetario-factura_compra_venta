<?php

namespace Models;

use App\Enums\Rol;
use App\Enums\TipoDocumento;
use App\Enums\EstadoGeneral;
use App\Models\Personas;
use PHPUnit\Framework\TestCase;

class PersonasTest extends TestCase
{
    public function testInsert()
    {
     $persona = new Personas(['id' => null,

    'tipodocumento' => TipoDocumento:: CEDULA,
    'documento' => 1002742949,
    'nombre' => 'Brayan',
    'apellido' => 'Curtidor',
    'telefono' => '322811936',
    'correo' => 'bscurtidor@isena.edu.co',
    'contrasena'=> '1234567',
    'rol'=> Rol:: ADMINISTRADOR,
    'estado' => EstadoGeneral::ACTIVO ]);

        $persona->insert();
        $this->assertSame(true, $persona -> PersonaRegistrada( 1002742949) );

    }
}
