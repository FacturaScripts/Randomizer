<?php
/**
 * This file is part of Randomizer plugin for FacturaScripts
 * Copyright (C) 2021-2025 Carlos Garcia Gomez <carlos@facturascripts.com>
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
use FacturaScripts\Dinamic\Model\CuentaBancoProveedor;
use FacturaScripts\Dinamic\Model\Proveedor;
use Faker;

/**
 * Description of Proveedores
 *
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 */
class Proveedores extends NewItems
{
    use GetIdsTrait;

    public static function create(int $number = 50): int
    {
        $faker = Faker\Factory::create('es_ES');

        static::dataBase()->beginTransaction();
        for ($generated = 0; $generated < $number; $generated++) {
            $proveedor = new Proveedor();
            $proveedor->acreedor = $faker->boolean();
            $proveedor->cifnif = static::cifnif();
            $proveedor->codpago = static::codpago();
            $proveedor->codretencion = static::codretencion();
            $proveedor->codserie = static::codserie();
            $proveedor->email = $faker->optional()->email();
            $proveedor->fax = $faker->optional(0.1)->phoneNumber();
            $proveedor->fechaalta = $faker->date();
            $proveedor->fechabaja = $faker->optional(0.1)->date();
            $proveedor->nombre = $faker->name();
            $proveedor->observaciones = $faker->optional()->paragraph();
            $proveedor->personafisica = $faker->boolean();
            $proveedor->razonsocial = $faker->optional()->company();
            $proveedor->regimeniva = static::regimenIVA();
            $proveedor->telefono1 = $faker->optional()->phoneNumber();
            $proveedor->telefono2 = $faker->optional()->phoneNumber();
            $proveedor->web = static::web($faker);

            if (false === $proveedor->save()) {
                break;
            }

            static::createBankAccounts($faker, $proveedor->codproveedor);
            static::createContacts($faker, $proveedor->codproveedor);

            self::setId($proveedor->primaryColumnValue());
        }

        static::dataBase()->commit();

        return $generated;
    }

    /**
     * @param Faker\Generator $faker
     * @param string $codproveedor
     */
    private static function createBankAccounts(&$faker, $codproveedor): void
    {
        $max = $faker->numberBetween(-1, 5);
        for ($index = 1; $index <= $max; $index++) {
            $bank = new CuentaBancoProveedor();
            $bank->descripcion = implode(' ', $faker->words());
            $bank->iban = $faker->iban('ES');
            $bank->swift = $faker->optional()->swiftBicNumber();
            $bank->codproveedor = $codproveedor;
            $bank->principal = ($index === 1);
            $bank->save();
        }
    }

    /**
     * @param Faker\Generator $faker
     * @param string $codproveedor
     */
    private static function createContacts(&$faker, $codproveedor): void
    {
        $max = $faker->numberBetween(-1, 5);
        for ($index = 1; $index <= $max; $index++) {
            $contact = new Contacto();
            Contactos::setContactData($faker, $contact);
            $contact->codproveedor = $codproveedor;
            $contact->save();
        }
    }
}
