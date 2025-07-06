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

use FacturaScripts\Dinamic\Model\Empresa;
use Faker;

/**
 * Description of Empresas
 *
 * @author Jose Antonio Cuello <yopli2000@gmail.com>
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 */
class Empresas extends NewItems
{
    use GetIdsTrait;

    public static function create(int $number = 5): int
    {
        $faker = Faker\Factory::create('es_ES');

        for ($generated = 0; $generated < $number; $generated++) {
            $empresa = new Empresa();
            $empresa->administrador = $faker->optional()->name();
            $empresa->apartado = $faker->optional(0.1)->postcode();
            $empresa->cifnif = static::cifnif();
            $empresa->ciudad = $faker->optional(0.7)->city();
            $empresa->codpais = static::codpais();
            $empresa->codpostal = $faker->optional()->postcode();
            $empresa->direccion = $faker->optional()->address();
            $empresa->email = $faker->optional()->email();
            $empresa->fax = $faker->optional(0.1)->phoneNumber();
            $empresa->fechaalta = $faker->optional()->date();
            $empresa->nombre = $faker->name();
            $empresa->nombrecorto = $faker->optional(0.5, $empresa->nombre)->firstName();
            $empresa->observaciones = $faker->optional()->paragraph();
            $empresa->personafisica = $faker->boolean();
            $empresa->provincia = $faker->optional()->state();
            $empresa->regimeniva = static::regimenIVA();
            $empresa->telefono1 = $faker->optional()->phoneNumber();
            $empresa->telefono2 = $faker->optional()->phoneNumber();
            $empresa->web = $faker->optional()->url();

            if ($empresa->exists()) {
                continue;
            }

            if (false === $empresa->save()) {
                break;
            }

            self::setId($empresa->primaryColumnValue());
        }

        return $generated;
    }
}
