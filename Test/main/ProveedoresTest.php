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

use FacturaScripts\Dinamic\Model\Proveedor;
use FacturaScripts\Plugins\Randomizer\Lib\Random\Proveedores;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class ProveedoresTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        $generated = Proveedores::create(7);
        $this->assertEquals(7, $generated);

        foreach (Proveedores::getIds() as $id) {
            $producto = new Proveedor();
            $this->assertTrue($producto->loadFromCode($id));
            $this->assertTrue($producto->delete());
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = Proveedores::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegative(): void
    {
        $generated = Proveedores::create(-1);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
