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

use FacturaScripts\Dinamic\Model\Familia;
use Faker;
use function mt_rand;

/**
 * Description of Familias
 *
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 */
class Familias extends NewItems
{

    /**
     *
     * @param int $number
     *
     * @return int
     */
    public static function create(int $number = 50): int
    {
        $faker = Faker\Factory::create('es_ES');

        $madre = null;
        for ($generated = 0; $generated < $number; $generated++) {
            $familia = new Familia();
            $familia->codfamilia = static::codeOrNull(8);
            $familia->descripcion = $faker->company();
            $familia->madre = mt_rand(0, 3) === 0 ? $madre : null;

            if ($familia->exists()) {
                continue;
            }

            if (false === $familia->save()) {
                break;
            }

            $madre = $familia->codfamilia;
        }

        return $generated;
    }
}
