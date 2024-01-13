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

use FacturaScripts\Dinamic\Model\AgenciaTransporte;
use Faker;

/**
 * Description of AgenciasTransportes
 *
 * @author Jose Antonio Cuello <yopli2000@gmail.com>
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 */
class AgenciasTransportes extends NewItems
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
            $agencia = new AgenciaTransporte();
            $agencia->activo = $faker->boolean();
            $agencia->codtrans = static::codeOrNull(8);
            $agencia->nombre = $faker->name();
            $agencia->telefono = $faker->optional()->phoneNumber();
            $agencia->web = static::web($faker);

            if ($agencia->exists()) {
                continue;
            }

            if (false === $agencia->save()) {
                break;
            }
        }

        return $generated;
    }
}
