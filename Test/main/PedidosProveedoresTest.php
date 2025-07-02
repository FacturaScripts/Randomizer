<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Dinamic\Model\PedidoProveedor;
use FacturaScripts\Plugins\Randomizer\Lib\Random\PedidosProveedores;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class PedidosProveedoresTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        $generated = PedidosProveedores::create(3);
        //$this->assertEquals(3, $generated);

        foreach (PedidosProveedores::getIds() as $id) {
            $pedidoprov = new PedidoProveedor();
            if ($pedidoprov->loadFromCode($id)) {
                $pedidoprov->delete();
            }
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = PedidosProveedores::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegative(): void
    {
        $generated = PedidosProveedores::create(-1);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
