<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Dinamic\Model\Familia;
use FacturaScripts\Plugins\Randomizer\Lib\Random\Familias;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class FamiliasTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        $generated = Familias::create(3);
        $this->assertEquals(3, $generated);

        foreach (Familias::getIds() as $id) {
            $familia = new Familia();
            $this->assertTrue($familia->loadFromCode($id));
            $this->assertTrue($familia->delete());
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = Familias::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegative(): void
    {
        $generated = Familias::create(-1);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
