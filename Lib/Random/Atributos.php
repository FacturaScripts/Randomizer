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

use FacturaScripts\Dinamic\Model\Atributo;
use FacturaScripts\Dinamic\Model\AtributoValor;
use Faker;

/**
 * Description of Atributos
 *
 * @author Jose Antonio Cuello <yopli2000@gmail.com>
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 */
class Atributos extends NewItems
{
    use GetIdsTrait;

    public static function create(int $number = 5): int
    {
        $faker = Faker\Factory::create('es_ES');

        for ($generated = 0; $generated < $number; $generated++) {
            $atributo = new Atributo();
            $atributo->nombre = implode(' ', $faker->words());

            if ($atributo->exists()) {
                continue;
            }

            if (false === $atributo->save()) {
                break;
            }

            self::createValues($faker, $atributo->codatributo);
            self::setId($atributo->primaryColumnValue());
        }

        return $generated;
    }

    /**
     * @param Faker $faker
     * @param string $parent
     */
    private static function createValues(&$faker, $parent): void
    {
        $max = $faker->numberBetween(1, 10);
        for ($index = 1; $index <= $max; $index++) {
            $value = new AtributoValor();
            $value->codatributo = $parent;
            $value->descripcion = $faker->name;
            $value->valor = implode(' ', $faker->words());
            if (false === $value->save()) {
                break;
            }
        }
    }
}
