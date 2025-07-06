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

use FacturaScripts\Dinamic\Model\Familia;
use FacturaScripts\Plugins\Randomizer\Lib\Random\Familias;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class FamiliasTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        // creamos 3 familias aleatorias
        Familias::clear();
        $generated = Familias::create(3);
        $this->assertEquals(3, $generated);

        // comprobamos que se han creado y los eliminamos
        foreach (Familias::getIds() as $id) {
            $familia = new Familia();
            $this->assertTrue($familia->loadFromCode($id));
            $this->assertTrue($familia->delete());
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = Familias::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegative(): void
    {
        $generated = Familias::create(-1);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
