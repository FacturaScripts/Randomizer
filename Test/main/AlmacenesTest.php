<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Plugins\Randomizer\Lib\Random\Almacenes;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use FacturaScripts\Dinamic\Model\Almacen;
use PHPUnit\Framework\TestCase;

final class AlmacenesTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        $generated = Almacenes::create(8);
        $this->assertEquals(8, $generated);

        foreach (Almacenes::getIds() as $id) {
            $Almacen = new Almacen();
            if ($Almacen->loadFromCode($id)) {
                $Almacen->delete();
            }
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = Almacenes::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegativeNumber(): void
    {
        $generated = Almacenes::create(-3);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
