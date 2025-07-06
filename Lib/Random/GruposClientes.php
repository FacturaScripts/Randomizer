<?php
/**
 * This file is part of Randomizer plugin for FacturaScripts
 * Copyright (C) 2021 Carlos Garcia Gomez <carlos@facturascripts.com>
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

namespace FacturaScripts\Plugins\Randomizer\Lib\Random;

use FacturaScripts\Dinamic\Model\GrupoClientes;
use Faker;

/**
 * Description of GruposClientes
 *
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 */
class GruposClientes extends NewItems
{
    use GetIdsTrait;

    public static function create(int $number = 25): int
    {
        $faker = Faker\Factory::create('es_ES');

        for ($generated = 0; $generated < $number; $generated++) {
            $grupo = new GrupoClientes();
            $grupo->codgrupo = static::codeOrNull(6);
            $grupo->codtarifa = static::codtarifa();
            $grupo->nombre = $faker->word();

            if ($grupo->exists()) {
                continue;
            }

            if (false === $grupo->save()) {
                break;
            }

            self::setId($grupo->primaryColumnValue());
        }

        return $generated;
    }
}
