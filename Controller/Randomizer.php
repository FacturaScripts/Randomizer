<?php
/**
 * This file is part of Randomizer plugin for FacturaScripts
 * Copyright (C) 2017-2021 Carlos Garcia Gomez <carlos@facturascripts.com>
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
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller to generate random data
 *
 * @author Carlos García Gómez  <carlos@facturascripts.com>
 * @author Rafael San José      <info@rsanjoseo.com>
 * @author Jose Antonio Cuello  <yopli2000@gmail.com>
 */
class Randomizer extends Base\Controller
{

    /** @var string */
    public $option;

    /**
     * Contains the total quantity for each model.
     * @var array
     */
    public $totalCounter = [];

    /** @var array */
    public $buttonList = [];

    /** @var array */
    private $actionList = [];

    /**
     * Add a new button into group, for generate ramdom data.
     *
     * @param string $group
     * @param string $action
     * @param string $actionLabel
     * @param string $buttonLabel
     * @param string $buttonIcon
     * @param string $randomClass
     * @param string $modelClass
     */
    public function addButton($group, $action, $actionLabel, $buttonLabel, $buttonIcon, $randomClass, $modelClass)
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

    /**
     * Returns basic page attributes
     *
     * @return array
     */
    public function getPageData()
    {
        $pageData = parent::getPageData();
        $pageData['menu'] = 'admin';
        $pageData['title'] = 'generate-test-data';
        $pageData['icon'] = 'fas fa-flask';
        return $pageData;
    }

    /**
     * Runs the controller's private logic.
     *
     * @param Response                   $response
     * @param User                       $user
     * @param Base\ControllerPermissions $permissions
     */
    public function privateCore(&$response, $user, $permissions)
    {
        parent::privateCore($response, $user, $permissions);

        $this->loadButtons();
        $this->option = $this->request->get('gen', '');
        if ($this->option !== '') {
            $this->execAction();
            $this->redirect($this->url() . '?gen=' . $this->option, 5);
        }

        $this->getTotals();
    }

    /**
     * Executes selected action.
     */
    private function execAction()
    {
        foreach ($this->actionList as $action => $values) {
            if ($action != $this->option) {
                continue;
            }

            $itemClass = $values['items'];
            if (\class_exists($itemClass)) {
                $this->generateAction($values['label'], $itemClass::create());
            }
            break;
        }
    }

    /**
     *
     * @param string $label
     * @param int    $number
     */
    private function generateAction(string $label, int $number)
    {
        $this->toolBox()->i18nLog()->notice($label, ['%quantity%' => $number]);
        $this->toolBox()->i18nLog()->notice('randomizer-generating-more-items');
        return true;
    }

    /**
     * Set totalCounter key for each model.
     */
    private function getTotals()
    {
        foreach ($this->actionList as $tag => $values) {
            $modelName = $values['model'];
            if (false === \class_exists($modelName)) {
                $this->totalCounter[$tag] = 0;
                continue;
            }

            $model = new $modelName();
            $this->totalCounter[$tag] = $model->count();
        }
    }

    private function loadButtons()
    {
        $this->addButton('', 'empresas', 'generated-companies', 'companies', 'fas fa-building', 'Random\\Empresas', 'Empresa');
        $this->addButton('', 'almacenes', 'generated-warehouses', 'warehouses', 'fas fa-warehouse', 'Random\\Almacenes', 'Almacen');
        $this->addButton('', 'transportistas', 'generated-carriers', 'carriers', 'fas fa-truck', 'Random\\AgenciasTransportes', 'AgenciaTransporte');
        $this->addButton('', 'fabricantes', 'generated-manufacturers', 'manufacturers', 'fas fa-industry', 'Random\\Fabricantes', 'Fabricante');
        $this->addButton('', 'familias', 'generated-families', 'families', 'fas fa-sitemap', 'Random\\Familias', 'Familia');
        $this->addButton('', 'atributos', 'generated-attributes', 'attributes', 'fas fa-tshirt', 'Random\\Atributos', 'Atributo');
        $this->addButton('', 'productos', 'generated-products', 'products', 'fas fa-cubes', 'Random\\Productos', 'Producto');
        $this->addButton('', 'agentes', 'generated-agents', 'agents', 'fas fa-user-tie', 'Random\\Agentes', 'Agente');
        $this->addButton('', 'contactos', 'generated-contacts', 'contacts', 'fas fa-users', 'Random\\Contactos', 'Contacto');
        $this->addButton('', 'users', 'generated-users', 'users', 'fas fa-user-circle', 'Random\\Usuarios', 'User');

        $this->addButton('purchases', 'proveedores', 'generated-supplier', 'suppliers', 'fas fa-users', 'Random\\Proveedores', 'Proveedor');
        $this->addButton('purchases', 'presupuestosprov', 'generated-supplier-estimations', 'estimations', 'fas fa-copy', 'Random\\PresupuestosProveedores', 'PresupuestoProveedor');
        $this->addButton('purchases', 'pedidosprov', 'generated-supplier-orders', 'orders', 'fas fa-copy', 'Random\\PedidosProveedores', 'PedidoProveedor');
        $this->addButton('purchases', 'albaranesprov', 'generated-supplier-delivery-notes', 'delivery-notes', 'fas fa-copy', 'Random\\AlbaranesProveedores', 'AlbaranProveedor');

        /// TODO: añadir tarifas
        $this->addButton('sales', 'grupos', 'generated-customer-groups', 'customer-groups', 'fas fa-users-cog', 'Random\\GruposClientes', 'GrupoClientes');
        $this->addButton('sales', 'clientes', 'generated-customers', 'customers', 'fas fa-users', 'Random\\Clientes', 'Cliente');
        $this->addButton('sales', 'comisiones', 'generated-commissions', 'commissions', 'fas fa-percentage', 'Random\\Comisiones', 'Comision');
        $this->addButton('sales', 'presupuestoscli', 'generated-customer-estimations', 'estimations', 'fas fa-copy', 'Random\\PresupuestosClientes', 'PresupuestoCliente');
        $this->addButton('sales', 'pedidoscli', 'generated-customer-orders', 'orders', 'fas fa-copy', 'Random\\PedidosClientes', 'PedidoCliente');
        $this->addButton('sales', 'albaranescli', 'generated-customer-delivery-notes', 'delivery-notes', 'fas fa-copy', 'Random\\AlbaranesClientes', 'AlbaranCliente');

        $this->pipe('loadButtons');
    }
}
