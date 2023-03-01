<?php

namespace Test\Models;

use App\Models\DetalleCompras;
use App\Models\DetalleVentas;
use PHPUnit\Framework\TestCase;

class DetalleComprasTest extends TestCase
{
    public function testInsert()
    {
        $DetalleCompras = new DetalleCompras(['id' => 1,
            'cantidad' => 2,
            'valor' => 80000,
            'compra_id' => 42,
            'producto_id' => 16 ]);

        $DetalleCompras->insert();
        $this->assertSame(true, $DetalleCompras-> detallecompraRegistrada( 42) );

    }
}
