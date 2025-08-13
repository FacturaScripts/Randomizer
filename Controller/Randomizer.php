<?php
/**
 * This file is part of Randomizer plugin for FacturaScripts
 * Copyright (C) 2017-2024 Carlos Garcia Gomez <carlos@facturascripts.com>
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

namespace FacturaScripts\Plugins\Randomizer\Controller;

use FacturaScripts\Core\Base;
use FacturaScripts\Core\Model\User;
use FacturaScripts\Core\Tools;
use FacturaScripts\Dinamic\Model\Comision;
use FacturaScripts\Core\Response;

/**
 * Controller to generate random data
 *
 * @author Carlos García Gómez  <carlos@facturascripts.com>
 * @author Rafael San José      <info@rsanjoseo.com>
 * @author Jose Antonio Cuello  <yopli2000@gmail.com>
 */
class Randomizer extends Base\Controller
{
    /** @var array */
    private $actionList = [];

    /** @var array */
    public $buttonList = [];

    /** @var string */
    public $option;

    /** @var array */
    public $totalCounter = [];

    public function addButton(string $group, string $action, string $actionLabel, string $buttonLabel, string $buttonIcon, string $randomClass, string $modelClass): void
    {
        $this->buttonList[$group][] = [
            'action' => $action,
            'label' => $buttonLabel,
            'icon' => $buttonIcon
        ];

        $this->actionList[$action] = [
            'label' => $actionLabel,
            'items' => 'FacturaScripts\\Dinamic\\Lib\\' . $randomClass,
            'model' => 'FacturaScripts\\Dinamic\\Model\\' . $modelClass
        ];
    }

    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data['menu'] = 'admin';
        $data['title'] = 'generate-test-data';
        $data['icon'] = 'fa-solid fa-flask';
        return $data;
    }

    /**
     * Runs the controller's private logic.
     *
     * @param Response $response
     * @param User $user
     * @param Base\ControllerPermissions $permissions
     */
    public function privateCore(&$response, $user, $permissions)
    {
        parent::privateCore($response, $user, $permissions);

        $this->loadButtons();

        $option = $this->request->get('gen', '');
        if ($option !== '') {
            $this->execAction($option);
            $this->redirect($this->url() . '?gen=' . $option, 3);
        }

        $this->setTotals();
    }

    private function execAction(string $option): void
    {
        foreach ($this->actionList as $action => $values) {
            if ($action != $option) {
                continue;
            }

            $itemClass = $values['items'];
            if (class_exists($itemClass)) {
                $this->option = $option;
                $this->generateAction($values['label'], $itemClass::create());
            }
            break;
        }
    }

    private function generateAction(string $label, int $number): void
    {
        Tools::log()->notice($label, ['%quantity%' => $number]);
        Tools::log()->notice('randomizer-generating-more-items');
    }

    private function loadButtons(): void
    {
        $this->addButton('', 'empresas', 'generated-companies', 'companies', 'fa-solid fa-building', 'Random\\Empresas', 'Empresa');
        $this->addButton('', 'almacenes', 'generated-warehouses', 'warehouses', 'fa-solid fa-warehouse', 'Random\\Almacenes', 'Almacen');
        $this->addButton('', 'transportistas', 'generated-carriers', 'carriers', 'fa-solid fa-truck', 'Random\\AgenciasTransportes', 'AgenciaTransporte');
        $this->addButton('', 'fabricantes', 'generated-manufacturers', 'manufacturers', 'fa-solid fa-industry', 'Random\\Fabricantes', 'Fabricante');
        $this->addButton('', 'familias', 'generated-families', 'families', 'fa-solid fa-sitemap', 'Random\\Familias', 'Familia');
        $this->addButton('', 'atributos', 'generated-attributes', 'attributes', 'fa-solid fa-tshirt', 'Random\\Atributos', 'Atributo');
        $this->addButton('', 'productos', 'generated-products', 'products', 'fa-solid fa-cubes', 'Random\\Productos', 'Producto');
        $this->addButton('', 'agentes', 'generated-agents', 'agents', 'fa-solid fa-user-tie', 'Random\\Agentes', 'Agente');
        $this->addButton('', 'contactos', 'generated-contacts', 'contacts', 'fa-solid fa-users', 'Random\\Contactos', 'Contacto');
        $this->addButton('', 'users', 'generated-users', 'users', 'fa-solid fa-user-circle', 'Random\\Usuarios', 'User');

        $this->addButton('purchases', 'proveedores', 'generated-supplier', 'suppliers', 'fa-solid fa-users', 'Random\\Proveedores', 'Proveedor');
        $this->addButton('purchases', 'presupuestosprov', 'generated-supplier-estimations', 'estimations', 'fa-solid fa-copy', 'Random\\PresupuestosProveedores', 'PresupuestoProveedor');
        $this->addButton('purchases', 'pedidosprov', 'generated-supplier-orders', 'orders', 'fa-solid fa-copy', 'Random\\PedidosProveedores', 'PedidoProveedor');
        $this->addButton('purchases', 'albaranesprov', 'generated-supplier-delivery-notes', 'delivery-notes', 'fa-solid fa-copy', 'Random\\AlbaranesProveedores', 'AlbaranProveedor');

        $this->addButton('sales', 'grupos', 'generated-customer-groups', 'customer-groups', 'fa-solid fa-users-cog', 'Random\\GruposClientes', 'GrupoClientes');
        $this->addButton('sales', 'clientes', 'generated-customers', 'customers', 'fa-solid fa-users', 'Random\\Clientes', 'Cliente');

        if (class_exists(Comision::class)) {
            $this->addButton('sales', 'comisiones', 'generated-commissions', 'commissions', 'fa-solid fa-percentage', 'Random\\Comisiones', 'Comision');
        }

        $this->addButton('sales', 'presupuestoscli', 'generated-customer-estimations', 'estimations', 'fa-solid fa-copy', 'Random\\PresupuestosClientes', 'PresupuestoCliente');
        $this->addButton('sales', 'pedidoscli', 'generated-customer-orders', 'orders', 'fa-solid fa-copy', 'Random\\PedidosClientes', 'PedidoCliente');
        $this->addButton('sales', 'albaranescli', 'generated-customer-delivery-notes', 'delivery-notes', 'fa-solid fa-copy', 'Random\\AlbaranesClientes', 'AlbaranCliente');

        $this->pipe('loadButtons');
    }

    /**
     * Set totalCounter key for each model.
     */
    private function setTotals(): void
    {
        foreach ($this->actionList as $tag => $values) {
            $modelName = $values['model'];
            if (false === class_exists($modelName)) {
                $this->totalCounter[$tag] = 0;
                continue;
            }

            $model = new $modelName();
            $this->totalCounter[$tag] = $model->count();
        }
    }
}
