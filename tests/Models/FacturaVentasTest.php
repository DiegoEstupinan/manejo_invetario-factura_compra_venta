<?php

namespace Test\Models;

use App\Enums\EstadoGeneral;
use App\Enums\Rol;
use App\Enums\TipoDocumento;
use App\Models\FacturaVentas;
use App\Models\Personas;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class FacturaVentasTest extends TestCase
{

    public function testInsert()
    {
        $facturaventas = new FacturaVentas(['id' => null,
            'fecha' => "25-01-2021",
            'monto' => "80",
            'cliente_id' => 16,
            'estado' => EstadoGeneral::ACTIVO ]);

        $facturaventas->insert();
        $this->assertSame(true, $facturaventas -> facturaventasRegistrada( Carbon::parse('24-01-2021')) );

    }
}
