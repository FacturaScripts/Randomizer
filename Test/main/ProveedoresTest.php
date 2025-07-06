<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Dinamic\Model\Proveedor;
use FacturaScripts\Plugins\Randomizer\Lib\Random\Proveedores;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class ProveedoresTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        $generated = Proveedores::create(7);
        $this->assertEquals(7, $generated);

        foreach (Proveedores::getIds() as $id) {
            $producto = new Proveedor();
            $this->assertTrue($producto->loadFromCode($id));
            $this->assertTrue($producto->delete());
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = Proveedores::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegative(): void
    {
        $generated = Proveedores::create(-1);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
