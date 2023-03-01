<?php

namespace Test\Models;

use App\Enums\EstadoGeneral;
use App\Models\Clasificacion;
use App\Models\Productos;
use PHPUnit\Framework\TestCase;


class ProductosTest extends TestCase
{

    public function testInsert()
    {
        $producto = new Productos(['id' => null,
            'nombre' => 'serrucho',
            'stock' => 10,
            'precio' => 40000,
            'clasificacion_id' => 16,
            'estado' => EstadoGeneral::ACTIVO ,
            'medida_id' =>  16,
            'marca_id' =>  16,
            'material_id' =>  16]);
        $producto->insert();
        $this->assertSame(true, $producto -> productoRegistrado( 'serrucho') );

    }
}
