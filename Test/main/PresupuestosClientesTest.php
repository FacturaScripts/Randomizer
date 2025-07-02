<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Dinamic\Model\PresupuestoCliente;
use FacturaScripts\Plugins\Randomizer\Lib\Random\PresupuestosClientes;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class PresupuestosClientesTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        $generated = PresupuestosClientes::create(3);
        $this->assertEquals(3, $generated);

        foreach (PresupuestosClientes::getIds() as $id) {
            $presupuesto = new PresupuestoCliente();
            $presupuesto->loadFromCode($id);
            $presupuesto->delete();
        }
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
