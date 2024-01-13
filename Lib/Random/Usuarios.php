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

use FacturaScripts\Dinamic\Model\Role;
use FacturaScripts\Dinamic\Model\RoleUser;
use FacturaScripts\Dinamic\Model\User;
use Faker;

/**
 * Description of Usuarios
 *
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 */
class Usuarios extends NewItems
{

    /**
     *
     * @var Role[]
     */
    private static $roles;

    /**
     *
     * @param int $number
     *
     * @return int
     */
    public static function create(int $number = 25): int
    {
        $faker = Faker\Factory::create('es_ES');

        for ($generated = 0; $generated < $number; $generated++) {
            $user = new User();
            $user->nick = $faker->email();
            if ($user->exists()) {
                continue;
            }

            $user->admin = $faker->boolean(5);
            $user->codagente = static::codagente();
            $user->codalmacen = static::codalmacen();
            $user->creationdate = $faker->date();
            $user->email = $user->nick;
            $user->enabled = $faker->boolean(90);
            $user->lastactivity = $faker->date();
            $user->lastip = $faker->optional()->ipv4();
            $user->newPassword = $user->newPassword2 = $faker->password();

            if (false === $user->save()) {
                break;
            }

            if (false === $user->admin) {
                static::setRole($user);
            }
        }

        return $generated;
    }

    /**
     * 
     * @param User $user
     */
    private static function setRole($user)
    {
        if (null === self::$roles) {
            $role = new Role();
            self::$roles = $role->all();
        }

        if (\count(self::$roles) <= 1) {
            return;
        }

        \shuffle(self::$roles);
        foreach (self::$roles as $role) {
            $roleUser = new RoleUser();
            $roleUser->codrole = $role->codrole;
            $roleUser->nick = $user->nick;
            $roleUser->save();
            break;
        }
    }
}
