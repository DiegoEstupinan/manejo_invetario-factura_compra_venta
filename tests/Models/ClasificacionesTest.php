<?php

namespace Models;

use App\Models\Clasificaciones;
use PHPUnit\Framework\TestCase;

class ClasificacionesTest extends TestCase
{

    public function testInsert()
    {

        $clasificacion =new Clasificaciones(['id' => null,
          'nombre' => 'Mineria']);
        $clasificacion->insert();
        $this->assertSame(true,  $clasificacion->clasificacionRegistrada( 'Mineria'));
    }
}
