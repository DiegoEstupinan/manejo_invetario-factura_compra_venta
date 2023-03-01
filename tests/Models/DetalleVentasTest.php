<?php

namespace Test\Models;

use  App\Enums\EstadoGeneral;
use App\Models\DetalleVentas;
use App\Models\FacturaVentas;
use PHPUnit\Framework\TestCase;

class DetalleVentasTest extends TestCase
{
    public function testInsert()
    {
        $detalle_venta = new DetalleVentas(['id' => 28,
            'cantidad' => 12,
            'valor' => 20000,
            'facturaventa_id' => 44,
            'producto_id' => 16 ]);

        $detalle_venta->insert();
        $this->assertSame(true, $detalle_venta-> detalleventaRegistrada( 28) );

    }
}
