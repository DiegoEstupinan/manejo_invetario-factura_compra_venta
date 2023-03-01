<?php

namespace Test\Models;

use App\Enums\EstadoGeneral;
use App\Models\Marcas;
use PHPUnit\Framework\TestCase;

class MarcasTest extends TestCase
{

    public function testInsert()
    {
        $marcas =new Marcas(['id' => null,
            'nombre' => 'Caballo',
             'estado' => EstadoGeneral::ACTIVO]);

        $marcas->insert();
        $this->assertSame(true, $marcas->marcasRegistrada( 'Caballo'));

    }


}

