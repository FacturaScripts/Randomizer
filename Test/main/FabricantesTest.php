<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Core\Model\Fabricante;
use FacturaScripts\Plugins\Randomizer\Lib\Random\Fabricantes;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class FabricantesTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        $generated = Fabricantes::create(3);
        $this->assertEquals(3, $generated);

        foreach (Fabricantes::getIds() as $id) {
            $fabricante = new Fabricante();
            $this->assertTrue($fabricante->loadFromCode($id));
            $this->assertTrue($fabricante->delete());
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = Fabricantes::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegative(): void
    {
        $generated = Fabricantes::create(-1);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
