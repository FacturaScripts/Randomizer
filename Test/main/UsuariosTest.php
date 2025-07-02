<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Dinamic\Model\User;
use FacturaScripts\Plugins\Randomizer\Lib\Random\Usuarios;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class UsuariosTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        $generated = Usuarios::create(7);
        //$this->assertEquals(7, $generated);

        foreach (Usuarios::getIds() as $id) {
            $user = new User();
            $this->assertTrue($user->loadFromCode($id));
            $this->assertTrue($user>delete());
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = Usuarios::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegative(): void
    {
        $generated = Usuarios::create(-1);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
