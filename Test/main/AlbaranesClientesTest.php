<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Plugins\Randomizer\Lib\Random\AlbaranesClientes;
use FacturaScripts\Dinamic\Model\AlbaranCliente;
use FacturaScripts\Test\Traits\DefaultSettingsTrait;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class AlbaranesClientesTest extends TestCase
{
    use LogErrorsTrait;
    use DefaultSettingsTrait;

    public static function setUpBeforeClass(): void
    {
        self::setDefaultSettings();
    }

    public function testCreate(): void
    {
        $generated = AlbaranesClientes::create(5);
        $this->assertEquals(5, $generated);

        foreach (AlbaranesClientes::getIds() as $id) {
            $albaran = new AlbaranCliente();
            $this->assertTrue($albaran->loadFromCode($id));
            $this->assertTrue($albaran->delete());
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = AlbaranesClientes::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegative(): void
    {
        $generated = AlbaranesClientes::create(-10);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
