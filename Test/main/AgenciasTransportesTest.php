<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Plugins\Randomizer\Lib\Random\AgenciasTransportes;
use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Dinamic\Model\AgenciaTransporte;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class AgenciasTransportesTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        $generated = AgenciasTransportes::create(7);
        $this->assertEquals(7, $generated);
        
        $Codtrans = AgenciasTransportes::getCodtrans();
        $AgenciastransModel = new AgenciaTransporte();
        foreach ($Codtrans as $value) {
            $where = [new DataBaseWhere('codtrans', $value)];
            foreach($AgenciastransModel->all($where) as $agenciatrans) {
                $agenciatrans->delete();
            }
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = AgenciasTransportes::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegative(): void
    {
        $generated = AgenciasTransportes::create(-5);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
