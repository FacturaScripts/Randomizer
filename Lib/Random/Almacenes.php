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

use FacturaScripts\Dinamic\Model\Almacen;
use Faker;

/**
 * Description of Almacenes
 *
 * @author Jose Antonio Cuello <yopli2000@gmail.com>
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 */
class Almacenes extends NewItems
{

    /**
     *
     * @param int $number
     *
     * @return int
     */
    public static function create(int $number = 5): int
    {
        $faker = Faker\Factory::create('es_ES');

        for ($generated = 0; $generated < $number; $generated++) {
            $almacen = new Almacen();
            $almacen->apartado = $faker->optional(0.1)->postcode();
            $almacen->ciudad = $faker->optional()->city();
            $almacen->codalmacen = static::codeOrNull(4);
            $almacen->codpais = static::codpais();
            $almacen->codpostal = $faker->optional()->postcode();
            $almacen->direccion = $faker->optional()->address();
            $almacen->idempresa = static::idempresa();
            $almacen->nombre = $faker->company();
            $almacen->provincia = $faker->optional()->state();
            $almacen->telefono = $faker->optional()->phoneNumber();

            if ($almacen->exists()) {
                continue;
            }

            if (false === $almacen->save()) {
                break;
            }
        }

        return $generated;
    }
}
