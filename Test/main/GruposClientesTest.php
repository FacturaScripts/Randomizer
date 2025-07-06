<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Dinamic\Model\GrupoClientes;
use FacturaScripts\Plugins\Randomizer\Lib\Random\GruposClientes;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class GruposClientesTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        $generated = GruposClientes::create(3);
        $this->assertEquals(3, $generated);

        foreach (GruposClientes::getIds() as $id) {
            $grupocli = new GrupoClientes();
            $this->assertTrue($grupocli->loadFromCode($id));
            $this->assertTrue($grupocli->delete());
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = GruposClientes::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegative(): void
    {
        $generated = GruposClientes::create(-1);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
