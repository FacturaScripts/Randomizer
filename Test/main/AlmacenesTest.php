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

use FacturaScripts\Plugins\Randomizer\Lib\Random\Almacenes;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use FacturaScripts\Dinamic\Model\Almacen;
use PHPUnit\Framework\TestCase;

final class AlmacenesTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        // creamos 8 almacenes
        $generated = Almacenes::create(8);
        $this->assertEquals(8, $generated);

        // comprobamos que se han creado y los eliminamos
        foreach (Almacenes::getIds() as $id) {
            $almacen = new Almacen();
            $this->assertTrue($almacen->loadFromCode($id));
            $this->assertTrue($almacen->delete());
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = Almacenes::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegativeNumber(): void
    {
        $generated = Almacenes::create(-3);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
