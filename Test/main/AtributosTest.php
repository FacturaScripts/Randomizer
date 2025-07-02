<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Plugins\Randomizer\Lib\Random\Atributos;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use FacturaScripts\Dinamic\Model\Atributo;
use PHPUnit\Framework\TestCase;

final class AtributosTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        $generated = Atributos::create(6);
        $this->assertEquals(6, $generated);

        foreach (Atributos::getIds() as $id) {
            $Atributo = new Atributo();
            $this->assertTrue($Atributo->loadFromCode($id));
            $this->assertTrue($Atributo->delete());
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = Atributos::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegative(): void
    {
        $generated = Atributos::create(-5);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}