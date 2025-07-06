<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Dinamic\Model\Producto;
use FacturaScripts\Plugins\Randomizer\Lib\Random\Productos;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class ProductosTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        $generated = Productos::create(7);
        $this->assertEquals(7, $generated);

        foreach (Productos::getIds() as $id) {
            $producto = new Producto();
            $this->assertTrue($producto->loadFromCode($id));
            $this->assertTrue($producto->delete());
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = Productos::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegative(): void
    {
        $generated = Productos::create(-1);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
