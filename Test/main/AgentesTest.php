<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Dinamic\Model\User;
use FacturaScripts\Plugins\Randomizer\Lib\Random\Agentes;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use FacturaScripts\Dinamic\Model\Agente;
use PHPUnit\Framework\TestCase;

final class AgentesTest extends TestCase
{
    use LogErrorsTrait;

    public static function setUpBeforeClass(): void
    {
        new User();
    }

    public function testCreate(): void
    {
        $generated = Agentes::create(7);
        $this->assertEquals(7, $generated);

        foreach (Agentes::getIds() as $id) {
            $Agente = new Agente();
            $this->assertTrue($Agente->loadFromCode($id));
            $this->assertTrue($Agente->delete());
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = Agentes::create(0);
        $this->assertEquals(0, $generated);
    }
    
    public function testCreateWithNegative(): void
    {
        $generated = Agentes::create(-10);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
