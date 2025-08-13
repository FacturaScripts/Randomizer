<?php
/**
 * This file is part of Randomizer plugin for FacturaScripts
 * Copyright (C) 2025 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace FacturaScripts\Test\Plugins;

use FacturaScripts\Dinamic\Model\PresupuestoProveedor;
use FacturaScripts\Dinamic\Model\Proveedor;
use FacturaScripts\Plugins\Randomizer\Lib\Random\PresupuestosProveedores;
use FacturaScripts\Plugins\Randomizer\Lib\Random\Proveedores;
use FacturaScripts\Test\Traits\DefaultSettingsTrait;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class PresupuestosProveedoresTest extends TestCase
{
    use DefaultSettingsTrait;
    use LogErrorsTrait;

    public static function setUpBeforeClass(): void
    {
        self::setDefaultSettings();
    }

    public function testCreate(): void
    {
        // creamos 6 proveedores
        $this->assertEquals(6, PresupuestosProveedores::create(6));

        // creamos 7 presupuestos
        PresupuestosProveedores::clear();
        $generated = PresupuestosProveedores::create(7);
        $this->assertEquals(7, $generated);

        // comprobamos que se han creado y los eliminamos
        foreach (PresupuestosProveedores::getIds() as $id) {
            $doc = new PresupuestoProveedor();
            $this->assertTrue($doc->loadFromCode($id));
            $this->assertTrue($doc->delete());
        }

        // eliminamos los proveedores
        foreach (Proveedores::getIds() as $id) {
            $proveedor = new Proveedor();
            $this->assertTrue($proveedor->loadFromCode($id));
            $this->assertTrue($proveedor->delete());
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = PresupuestosProveedores::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegative(): void
    {
        $generated = PresupuestosProveedores::create(-1);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
