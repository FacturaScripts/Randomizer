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

use FacturaScripts\Core\Lib\Calculator;
use FacturaScripts\Core\Tools;

use FacturaScripts\Dinamic\Model\PedidoCliente;
use Faker;

/**
 * Description of PedidosClientes
 *
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 */
class PedidosClientes extends NewBusinessDocument
{
    use GetIdsTrait;

    public static function create(int $number = 10): int
    {
        $faker = Faker\Factory::create('es_ES');
        $lineMultiplier = $faker->optional(0.1, 1)->numberBetween(2, 99);

        static::dataBase()->beginTransaction();

        for ($generated = 0; $generated < $number; $generated++) {

            $doc = new PedidoCliente();
            $doc->setSubject(static::cliente());
            $doc->codagente = static::codagente();
            $doc->codalmacen = static::codalmacen();
            $doc->codigoenv = $faker->optional()->isbn13();
            $doc->codpago = static::codpago();
            $doc->codtrans = static::codtrans();
            $doc->codserie = static::codserie();
            $doc->dtopor1 = $faker->optional(0.1)->numberBetween(1, 90);
            $doc->dtopor2 = $faker->optional(0.1)->numberBetween(1, 90);
            $doc->fecha = static::fecha();
            $doc->hora = static::hora();
            $doc->nick = static::nick(true);
            $doc->numero2 = static::referencia();
            $doc->observaciones = $faker->optional()->text();

            if (false === $doc->save()) {
                static::dataBase()->rollback();

                return $generated;
            }

            $lines = static::createLines($faker, $doc, $faker->numberBetween(1, 49) * $lineMultiplier);
            if (false === Calculator::calculate($doc, $lines, true)) {
                static::dataBase()->rollback();

                return $generated;
            }

            self::setId($doc->primaryColumnValue());
        }

        static::dataBase()->commit();

        return $generated;
    }
}
