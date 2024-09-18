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

use FacturaScripts\Core\Base\Calculator;
use FacturaScripts\Core\Model\Base\BusinessDocument;
use FacturaScripts\Core\Model\Base\BusinessDocumentLine;
use FacturaScripts\Dinamic\Model\AgenciaTransporte;
use Faker;
use function mt_rand;
use function shuffle;

/**
 * Set of methods common to the different Business Documents.
 *
 * @author Jose Antonio Cuello <yopli2000@gmail.com>
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 */
abstract class NewBusinessDocument extends NewItems
{

    /**
     *
     * @var AgenciaTransporte[]
     */
    private static $agenciastrans = null;

    /**
     *
     * @return string
     */
    protected static function codtrans()
    {
        if (null === self::$agenciastrans) {
            $agencia = new AgenciaTransporte();
            self::$agenciastrans = $agencia->all();
        }

        shuffle(self::$agenciastrans);
        return empty(self::$agenciastrans) || mt_rand(0, 2) > 0 ? null : self::$agenciastrans[0]->codtrans;
    }

    /**
     * Add a number of lines to the indicated document.
     *
     * @param Faker\Generator $faker
     * @param BusinessDocument $document
     * @param int $numLines
     */
    protected static function createLines(&$faker, &$document, int $numLines = 1)
    {
        for ($line = 0; $line < $numLines; $line++) {
            $newLine = static::getNewLine($faker, $document);
            $newLine->cantidad = $faker->optional(0.1, 1)->randomFloat(2, -9, 999);
            $newLine->dtopor = $faker->optional(0.1, 0)->numberBetween(1, 90);
            if (false === $newLine->save()) {
                break;
            }
        }
    }

    /**
     *
     * @param BusinessDocument $document
     */
    protected static function recalculate(&$document)
    {
        $lines = $document->getLines();
        Calculator::calculate($document, $lines, true);
    }

    /**
     * @param Faker\Generator $faker
     * @param BusinessDocument $document
     *
     * @return BusinessDocumentLine
     */
    private static function getNewLine(&$faker, &$document)
    {
        $reference = static::referencia();
        if (empty($reference)) {
            $newLine = $document->getNewLine();
            $newLine->descripcion = $faker->text();
            $newLine->pvpunitario = $faker->numberBetween(0, 49) * $faker->optional(0.1, 1)->randomFloat(2, 0.01, 100);
            return $newLine;
        }

        return $document->getNewProductLine($reference);
    }
}
