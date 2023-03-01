<?php

namespace Test\Models;

use App\Models\Clasificacion;
use App\Models\Medidas;
use PHPUnit\Framework\TestCase;

class MedidasTest extends TestCase
{

    public function testInsert()
    {
        $medida =new Medidas(['id' => null,
            'nombre' => 'Kilo']);
        $medida->insert();
        $this->assertSame(true,  $medida->medidaRegistrada( 'Kilo'));
    }
}
