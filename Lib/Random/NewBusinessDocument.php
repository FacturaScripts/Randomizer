<?php
/**
 * This file is part of Randomizer plugin for FacturaScripts
 * Copyright (C) 2021-2024 Carlos Garcia Gomez <carlos@facturascripts.com>
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

use FacturaScripts\Core\Model\Base\BusinessDocument;
use FacturaScripts\Core\Model\Base\BusinessDocumentLine;
use FacturaScripts\Core\Where;
use FacturaScripts\Dinamic\Model\AgenciaTransporte;
use FacturaScripts\Dinamic\Model\Variante;
use Faker;
use Faker\Generator;

/**
 * Set of methods common to the different Business Documents.
 *
 * @author Jose Antonio Cuello <yopli2000@gmail.com>
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 */
abstract class NewBusinessDocument extends NewItems
{
    /** @var AgenciaTransporte[] */
    private static $agenciastrans = null;

    protected static function codtrans(): ?string
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
     * @param Generator $faker
     * @param BusinessDocument $document
     * @param int $numLines
     * @return array
     */
    protected static function createLines(&$faker, &$document, int $numLines = 1): array
    {
        $lines = [];

        for ($line = 0; $line < $numLines; $line++) {
            $newLine = static::getNewLine($faker, $document);
            $newLine->cantidad = $faker->optional(0.1, 1)->randomFloat(2, -9, 999);
            $newLine->dtopor = $faker->optional(0.1, 0)->numberBetween(1, 90);

            $lines[] = $newLine;
        }

        return $lines;
    }

    /**
     * @param Faker\Generator $faker
     * @param BusinessDocument $document
     *
     * @return BusinessDocumentLine
     */
    private static function getNewLine(&$faker, &$document): BusinessDocumentLine
    {
        $reference = static::referencia();

        // buscamos la variante
        $variant = Variante::findWhere([Where::eq('referencia', $reference)]);

        if (empty($reference) || empty($variant) || $variant->getProducto()->ventasinstock === false) {
            $newLine = $document->getNewLine();
            $newLine->descripcion = $faker->text();
            $newLine->pvpunitario = $faker->numberBetween(0, 49) * $faker->optional(0.1, 1)
                    ->randomFloat(2, 0.01, 100);

            return $newLine;
        }

        return $document->getNewProductLine($reference);
    }
}
