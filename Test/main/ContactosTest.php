<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;
use FacturaScripts\Dinamic\Model\Contacto;
use FacturaScripts\Plugins\Randomizer\Lib\Random\Contactos;

final class ContactosTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        $generated = Contactos::create(3);
        $this->assertEquals(3, $generated);

        foreach (Contactos::getIds() as $id) {
            $contacto = new Contacto();
            $this->assertTrue($contacto->loadFromCode($id));
            $this->assertTrue($contacto->delete());
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = Contactos::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegative(): void
    {
        $generated = Contactos::create(-1);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
