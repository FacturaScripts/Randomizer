<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Dinamic\Model\PedidoCliente;
use FacturaScripts\Plugins\Randomizer\Lib\Random\GetIdsTrait;
use FacturaScripts\Plugins\Randomizer\Lib\Random\PedidosClientes;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class PedidosClientesTest extends TestCase
{
    use LogErrorsTrait;
    use GetIdsTrait;

    public function testCreate(): void
    {
        $generated = PedidosClientes::create(3);
        $this->assertEquals(3, $generated);

        foreach (self::getIds() as $id) {
            $pedidocli = new PedidoCliente();
            if ($pedidocli->loadFromCode($id)) {
                $pedidocli->delete();
            }
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = PedidosClientes::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegative(): void
    {
        $generated = PedidosClientes::create(-1);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
