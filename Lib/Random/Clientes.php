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

use FacturaScripts\Dinamic\Model\Cliente;
use FacturaScripts\Dinamic\Model\Contacto;
use FacturaScripts\Dinamic\Model\CuentaBancoCliente;
use Faker;

/**
 * Description of Clientes
 *
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 */
class Clientes extends NewItems
{

    use GetIdsTrait;

    /**
     *
     * @param int $number
     *
     * @return int
     */
    public static function create(int $number = 50): int
    {
        $faker = Faker\Factory::create('es_ES');

        static::dataBase()->beginTransaction();
        for ($generated = 0; $generated < $number; $generated++) {
            $cliente = new Cliente();
            $cliente->cifnif = static::cifnif();
            $cliente->codagente = static::codagente();
            $cliente->codgrupo = static::codgrupo();
            $cliente->codpago = static::codpago();
            $cliente->codretencion = static::codretencion();
            $cliente->codserie = static::codserie();
            $cliente->email = $faker->optional()->email();
            $cliente->fax = $faker->optional(0.1)->phoneNumber();
            $cliente->fechaalta = $faker->date();
            $cliente->fechabaja = $faker->optional(0.1)->date();
            $cliente->nombre = $faker->name();
            $cliente->observaciones = $faker->optional()->paragraph();
            $cliente->personafisica = $faker->boolean();
            $cliente->razonsocial = $faker->optional()->company();
            $cliente->regimeniva = static::regimenIVA();
            $cliente->telefono1 = $faker->optional()->phoneNumber();
            $cliente->telefono2 = $faker->optional()->phoneNumber();
            $cliente->web = static::web($faker);

            if ($cliente->exists()) {
                continue;
            }

            if (false === $cliente->save()) {
                break;
            }

            static::createBankAccounts($faker, $cliente->codcliente);
            static::createContacts($faker, $cliente->codcliente);
            self::setId($cliente->primaryColumnValue());
        }

        static::dataBase()->commit();
        return $generated;
    }

    /**
     *
     * @param Faker\Generator $faker
     * @param string $codcliente
     */
    private static function createBankAccounts(&$faker, $codcliente)
    {
        $max = $faker->numberBetween(-1, 5);
        for ($index = 1; $index <= $max; $index++) {
            $bank = new CuentaBancoCliente();
            $bank->descripcion = implode(' ', $faker->words());
            $bank->iban = $faker->iban('ES');
            $bank->swift = $faker->optional()->swiftBicNumber();
            $bank->codcliente = $codcliente;
            $bank->principal = ($index === 1);
            $bank->fmandato = $faker->date();
            $bank->save();
        }
    }

    /**
     *
     * @param Faker\Generator $faker
     * @param string $codcliente
     */
    private static function createContacts(&$faker, $codcliente)
    {
        $max = $faker->numberBetween(-1, 5);
        for ($index = 1; $index <= $max; $index++) {
            $contact = new Contacto();
            Contactos::setContactData($faker, $contact);
            $contact->codcliente = $codcliente;
            $contact->save();
        }
    }
}
