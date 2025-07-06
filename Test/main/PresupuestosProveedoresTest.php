<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Dinamic\Model\PresupuestoProveedor;
use FacturaScripts\Plugins\Randomizer\Lib\Random\PresupuestosProveedores;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class PresupuestosProveedoresTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        $generated = PresupuestosProveedores::create(7);
        //$this->assertEquals(7, $generated);

        foreach (PresupuestosProveedores::getIds() as $id) {
            $presupuestoprov = new PresupuestoProveedor();
            $this->assertTrue($presupuestoprov->loadFromCode($id));
            $this->assertTrue($presupuestoprov->delete());
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = PresupuestosProveedores::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegative(): void
    {
        $generated = PresupuestosProveedores::create(-1);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
