<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Plugins\Randomizer\Lib\Random\AgenciasTransportes;
use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Dinamic\Model\AgenciaTransporte;
use FacturaScripts\Plugins\Randomizer\Lib\Random\GetIdsTrait;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class AgenciasTransportesTest extends TestCase
{
    use LogErrorsTrait;
    use GetIdsTrait;

    public function testCreate(): void
    {
        $generated = AgenciasTransportes::create(7);
        $this->assertEquals(7, $generated);
        
        foreach (self::getIds() as $id) {
            $agencia = new AgenciaTransporte();
            if ($agencia->loadFromCode($id)) {
                $agencia->delete();
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
