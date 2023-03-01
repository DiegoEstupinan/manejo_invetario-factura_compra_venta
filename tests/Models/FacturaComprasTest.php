<?php

namespace Test\Models;

use App\Enums\EstadoGeneral;
use App\Models\FacturaCompras;
use App\Models\FacturaVentas;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class FacturaComprasTest extends TestCase
{

    public function testInsert()
    {
        $facturacompras = new FacturaCompras(['id' => null,
            'fecha' => "28-02-2021",
            'monto' => "5",
            'proveedor_id' => 18,
            'estado' => EstadoGeneral::ACTIVO]);

        $facturacompras->insert();
        $this->assertSame(true, $facturacompras->facturacomprasRegistrada(Carbon::parse('28-02-2021')));
    }

}

