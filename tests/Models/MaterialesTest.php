<?php

namespace Test\Models;

use App\Models\Clasificacion;
use App\Models\Materiales;
use PHPUnit\Framework\TestCase;

class MaterialesTest extends TestCase
{

    /**
     * @throws \Exception
     */
    public function testInsert()
    {
        $material =new Materiales(['id' => null,
            'nombre' => 'Cobre']);
        $material->insert();
        $this->assertSame(true,  $material->MaterialRegistrado( 'Cobre'));
    }
}
