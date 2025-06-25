<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Core\Model\AtributoValor as ModelAtributoValor;
use FacturaScripts\Plugins\Randomizer\Lib\Random\Atributos;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use FacturaScripts\Dinamic\Model\AtributoValor;
use FacturaScripts\Dinamic\Model\Atributo;
use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class AtributosTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        $generated = Atributos::create(6);
        $this->assertEquals(6, $generated);

        $atributos = new Atributo();
        $allAtributos = $atributos->all();
        $this->assertCount(5, $allAtributos);
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