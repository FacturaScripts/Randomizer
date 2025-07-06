<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Core\Model\AlbaranProveedor;
use FacturaScripts\Plugins\Randomizer\Lib\Random\AlbaranesProveedores;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class AlbaranesProveedoresTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        $generated = AlbaranesProveedores::create(7);
        //$this->assertEquals(7, $generated);

        foreach (AlbaranesProveedores::getIds() as $id) {
            $albaran = new AlbaranProveedor();
            $this->assertTrue($albaran->loadFromCode($id));
            $this->assertTrue($albaran->delete());
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = AlbaranesProveedores::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegativeNumber(): void
    {
        $generated = AlbaranesProveedores::create(-5);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
