<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Test\Traits\LogErrorsTrait;
use FacturaScripts\Dinamic\Model\Comision;
use FacturaScripts\Plugins\Randomizer\Lib\Random\Comisiones;
use PHPUnit\Framework\TestCase;

final class ComisionesTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        $generated = Comisiones::create(8);
        $this->assertEquals(8, $generated);

        foreach (Comisiones::getIds() as $id) {
            $comision = new Comision();
            if ($comision->loadFromCode($id)) {
                $comision->delete();
            }
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = Comisiones::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegative(): void
    {
        $generated = Comisiones::create(-1);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
