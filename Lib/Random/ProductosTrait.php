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

use FacturaScripts\Core\Tools;
use FacturaScripts\Dinamic\Model\Almacen;
use FacturaScripts\Dinamic\Model\AtributoValor;
use FacturaScripts\Dinamic\Model\Fabricante;
use FacturaScripts\Dinamic\Model\Familia;
use FacturaScripts\Dinamic\Model\Variante;

/**
 * Set of methods for obtaining data derived from products
 *
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 * @author Jose Antonio Cuello <yopli2000@gmail.com>
 */
trait ProductosTrait
{
    /**
     *
     * @var AtributoValor[]
     */
    protected static $attributes = null;

    /**
     *
     * @var Familia[]
     */
    protected static $families = null;

    /**
     *
     * @var Fabricante[]
     */
    protected static $manufacturers = null;

    /**
     *
     * @var Variante[]
     */
    protected static $variants = null;

    /**
     *
     * @var Almacen[]
     */
    protected static $warehouses = null;

    /**
     *
     * @return AtributoValor|null
     */
    protected static function atributo()
    {
        if (null === self::$attributes) {
            $attribute = new AtributoValor();
            self::$attributes = $attribute->all();
        }

        shuffle(self::$attributes);
        return empty(self::$attributes) || mt_rand(0, 2) === 0 ? null : self::$attributes[0];
    }

    /**
     *
     * @return string
     */
    protected static function codalmacen()
    {
        if (null === self::$warehouses) {
            $warehouse = new Almacen();
            self::$warehouses = $warehouse->all();
        }

        shuffle(self::$warehouses);
        return mt_rand(0, 2) === 0 ? self::$warehouses[0]->codalmacen : Tools::settings('default', 'codalmacen');
    }

    /**
     *
     * @return string|null
     */
    protected static function codfabricante()
    {
        if (null === self::$manufacturers) {
            $manufacturer = new Fabricante();
            self::$manufacturers = $manufacturer->all();
        }

        shuffle(self::$manufacturers);
        return empty(self::$manufacturers) || mt_rand(0, 3) === 0 ? null : self::$manufacturers[0]->codfabricante;
    }

    /**
     *
     * @return string|null
     */
    protected static function codfamilia()
    {
        if (null === self::$families) {
            $family = new Familia();
            self::$families = $family->all();
        }

        shuffle(self::$families);
        return empty(self::$families) || mt_rand(0, 3) === 0 ? null : self::$families[0]->codfamilia;
    }

    /**
     *
     * @return int|null
     */
    protected static function idproducto()
    {
        if (null === self::$variants) {
            $variant = new Variante();
            self::$variants = $variant->all([], static::variantOrder(), 0, 200);
        }

        shuffle(self::$variants);
        return empty(self::$variants) || mt_rand(0, 2) === 0 ? null : self::$variants[0]->idproducto;
    }

    /**
     *
     * @return string|null
     */
    protected static function referencia()
    {
        if (null === self::$variants) {
            $variant = new Variante();
            self::$variants = $variant->all([], static::variantOrder(), 0, 200);
        }

        shuffle(self::$variants);
        return empty(self::$variants) || mt_rand(0, 2) === 0 ? null : self::$variants[0]->referencia;
    }

    /**
     *
     * @return array
     */
    private static function variantOrder(): array
    {
        $fields = ['codbarras', 'coste', 'idproducto', 'idvariante', 'precio', 'referencia'];
        shuffle($fields);

        $options = ['ASC', 'DESC'];
        shuffle($options);

        return [$fields[0] => $options[0]];
    }
}
