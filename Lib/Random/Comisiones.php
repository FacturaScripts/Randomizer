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

use FacturaScripts\Dinamic\Model\Comision;
use Faker;

/**
 * Description of Comisiones
 *
 * @author Jose Antonio Cuello <yopli2000@gmail.com>
 */
class Comisiones extends NewItems
{

    private static array $Ids = [];

    /**
     *
     * @param int $number
     *
     * @return int
     */
    public static function create(int $number = 50): int
    {
        $faker = Faker\Factory::create('es_ES');

        for ($generated = 0; $generated < $number; $generated++) {
            $comision = new Comision();
            $comision->codagente = static::codagente();
            $comision->codcliente = static::codcliente();
            $comision->codfamilia = static::codfamilia();
            $comision->idempresa = static::idempresa();
            $comision->idproducto = static::idproducto();
            $comision->porcentaje = $faker->numberBetween(1, 30);
            $comision->prioridad = $faker->optional(0.1)->numberBetween(1, 10);

            if ($comision->exists()) {
                continue;
            }

            if (false === $comision->save()) {
                break;
            }
            self::$Ids[] = $comision->primaryColumnValue();
        }

        return $generated;
    }

    public static function getIds(): array
    {
        return self::$Ids;
    }
}
