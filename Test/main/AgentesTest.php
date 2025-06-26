<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Plugins\Randomizer\Lib\Random\Agentes;
use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use FacturaScripts\Dinamic\Model\Agente;
use PHPUnit\Framework\TestCase;

final class AgentesTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        $generated = Agentes::create(50);
        $this->assertEquals(50, $generated);

        $CodAgentes = Agentes::getCodagentes();
        $AgenteModel = new Agente();
        foreach ($CodAgentes as $value) {
            $where = [new DataBaseWhere('codagente', $value)];
            foreach($AgenteModel->all($where) as $agente) {
                $agente->delete();
            }
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
