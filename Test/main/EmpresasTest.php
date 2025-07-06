<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Test\Traits\LogErrorsTrait;
use FacturaScripts\Dinamic\Model\Empresa;
use FacturaScripts\Plugins\Randomizer\Lib\Random\Empresas;
use PHPUnit\Framework\TestCase;

final class EmpresasTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        $generated = Empresas::create(7);
        $this->assertEquals(7, $generated);

        foreach (Empresas::getIds() as $id) {
            $agencia = new Empresa();
            $this->assertTrue($agencia->loadFromCode($id));
            $this->assertTrue($agencia->delete());
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = Empresas::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegative(): void
    {
        $generated = Empresas::create(-1);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
