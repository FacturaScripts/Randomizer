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

use FacturaScripts\Dinamic\Model\Contacto;
use Faker;

/**
 * Description of Contactos
 *
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 */
class Contactos extends NewItems
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

        for ($generated = 0; $generated < $number; $generated++) {
            $contact = new Contacto();
            self::setContactData($faker, $contact);

            if ($contact->exists()) {
                continue;
            }

            if (false === $contact->save()) {
                break;
            }
        }

        return $generated;
    }

    /**
     *
     * @param Faker\Generator $faker
     * @param Contacto $contact
     */
    public static function setContactData(&$faker, &$contact)
    {
        $contact->aceptaprivacidad = $faker->boolean();
        $contact->admitemarketing = $faker->boolean();
        $contact->apartado = $faker->optional(0.1)->postcode();
        $contact->apellidos = $faker->optional(0.9)->lastName();
        $contact->cargo = $faker->optional()->jobTitle();
        $contact->cifnif = static::cifnif();
        $contact->ciudad = $faker->optional(0.7)->city();
        $contact->codpais = static::codpais();
        $contact->codpostal = $faker->optional()->postcode();
        $contact->descripcion = $faker->optional(0.1)->name();
        $contact->direccion = $faker->optional()->address();
        $contact->email = $faker->optional(0.9)->email();
        $contact->empresa = $faker->optional()->company();
        $contact->fax = $faker->optional(0.1)->phoneNumber();
        $contact->fechaalta = $faker->date();
        $contact->lastip = $faker->optional()->ipv4();
        $contact->nombre = $faker->firstName();
        $contact->observaciones = $faker->optional()->paragraph();
        $contact->personafisica = $faker->boolean(80);
        $contact->provincia = $faker->optional()->state();
        $contact->telefono1 = $faker->optional()->phoneNumber();
        $contact->telefono2 = $faker->optional()->phoneNumber();
        $contact->verificado = $faker->boolean();
    }
}
