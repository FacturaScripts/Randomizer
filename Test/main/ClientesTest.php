<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Plugins\Randomizer\Lib\Random\Clientes;
use FacturaScripts\Dinamic\Model\Cliente;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class ClientesTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        $generated = Clientes::create(7);
        $this->assertEquals(7, $generated);

        foreach (Clientes::getIds() as $id) {
            $cliente = new Cliente();
            if ($cliente->loadFromCode($id)) {
                $cliente->delete();
            }
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = Clientes::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegative(): void
    {
        $generated = Clientes::create(-1);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
