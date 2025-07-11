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

use FacturaScripts\Dinamic\Model\GrupoClientes;
use FacturaScripts\Plugins\Randomizer\Lib\Random\GruposClientes;
use FacturaScripts\Test\Traits\LogErrorsTrait;
use PHPUnit\Framework\TestCase;

final class GruposClientesTest extends TestCase
{
    use LogErrorsTrait;

    public function testCreate(): void
    {
        // creamos 3 grupos
        GruposClientes::clear();
        $generated = GruposClientes::create(3);
        $this->assertEquals(3, $generated);

        // comprobamos que se han creado y los eliminamos
        foreach (GruposClientes::getIds() as $id) {
            $grupo = new GrupoClientes();
            $this->assertTrue($grupo->loadFromCode($id));
            $this->assertTrue($grupo->delete());
        }
    }

    public function testCreateWithZero(): void
    {
        $generated = GruposClientes::create(0);
        $this->assertEquals(0, $generated);
    }

    public function testCreateWithNegative(): void
    {
        $generated = GruposClientes::create(-1);
        $this->assertEquals(0, $generated);
    }

    protected function tearDown(): void
    {
        $this->logErrors();
    }
}
