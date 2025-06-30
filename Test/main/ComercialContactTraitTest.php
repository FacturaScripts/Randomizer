<?php

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Plugins\Randomizer\Lib\Random\ComercialContactTrait;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class ComercialContactTraitTest extends TestCase
{
    use LogErrorsTrait;

    /*public function testCliente(): void
    {
        $customer = ComercialContactTrait::cliente();
        $this->assertNotEmpty($customer);
    }

    public function testCodagente(): void
    {
        $agent = ComercialContactTrait::agent();
        $this->assertNotNull($agent);
    }

    public function testCodcliente(): void
    {
        $customer = ComercialContactTrait::codcliente();
        $this->assertNotNull($customer);
    }

    public function testCodgrupo(): void
    {
        $customerGroup = ComercialContactTrait::codgrupo();
        $this->assertNotNull($customerGroup);
    }

    public function testProveedor(): void
    {
        $suppliers = ComercialContactTrait::proveedor();
        $this->assertNotEmpty($suppliers);
    }

    public function testRegimenIVA(): void
    {
        $values = ComercialContactTrait::regimenIVA();
        $this->assertIsString($values);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }*/
}
