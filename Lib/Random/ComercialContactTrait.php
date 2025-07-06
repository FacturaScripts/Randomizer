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

use FacturaScripts\Dinamic\Lib\RegimenIVA;
use FacturaScripts\Dinamic\Model\Agente;
use FacturaScripts\Dinamic\Model\Cliente;
use FacturaScripts\Dinamic\Model\GrupoClientes;
use FacturaScripts\Dinamic\Model\Proveedor;

/**
 * Set of methods for obtaining data derived from commercials contacts
 *
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 */
trait ComercialContactTrait
{
    /**
     * @var Agente[]
     */
    protected static $agents = null;

    /**
     * @var Cliente[]
     */
    protected static $customers = null;

    /**
     * @var GrupoClientes[]
     */
    protected static $customerGroups = null;

    /**
     * @var Proveedor[]
     */
    protected static $suppliers = null;

    /**
     * @return Cliente
     */
    protected static function cliente()
    {
        if (null === self::$customers) {
            $customer = new Cliente();
            self::$customers = $customer->all();
        }

        shuffle(self::$customers);
        return empty(self::$customers) ? new Cliente() : self::$customers[0];
    }

    /**
     * @return string
     */
    protected static function codagente(): ?string
    {
        if (null === self::$agents) {
            $agent = new Agente();
            self::$agents = $agent->all();
        }

        shuffle(self::$agents);
        return empty(self::$agents) || mt_rand(0, 3) === 0 ? null : self::$agents[0]->codagente;
    }

    /**
     * @return string|null
     */
    protected static function codcliente(): ?string
    {
        if (null === self::$customers) {
            $customer = new Cliente();
            self::$customers = $customer->all();
        }

        shuffle(self::$customers);
        return empty(self::$customers) || mt_rand(0, 3) === 0 ? null : self::$customers[0]->codcliente;
    }

    /**
     * @return string
     */
    protected static function codgrupo(): ?string
    {
        if (null === self::$customerGroups) {
            $customerGroup = new GrupoClientes();
            self::$customerGroups = $customerGroup->all();
        }

        shuffle(self::$customerGroups);
        return empty(self::$customerGroups) || mt_rand(0, 2) === 0 ? null : self::$customerGroups[0]->codgrupo;
    }

    /**
     * @return Proveedor
     */
    protected static function proveedor()
    {
        if (null === self::$suppliers) {
            $supplier = new Proveedor();
            self::$suppliers = $supplier->all();
        }

        shuffle(self::$suppliers);
        return empty(self::$suppliers) ? new Proveedor() : self::$suppliers[0];
    }

    /**
     * @return string
     */
    protected static function regimenIVA(): string
    {
        $values = RegimenIVA::all();
        shuffle($values);
        return array_keys($values)[0];
    }
}
