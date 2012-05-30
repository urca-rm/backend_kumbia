<?php

/**
 * Backend - KumbiaPHP Backend
 * PHP version 5
 * LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * ERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Libs
 * @license http://www.gnu.org/licenses/agpl.txt GNU AFFERO GENERAL PUBLIC LICENSE version 3.
 * @author Manuel José Aguirre Garcia <programador.manuel@gmail.com>
 */
Load::lib('backend/auth_abstract');

class MyAuth extends AuthAbstract
{

    /**
     * Namespace de las cookies y el hash de clave que se va a encriptar
     * Recordar que si se cambian, se deben actualizar las claves en la bd.
     */ 
    protected static $_clave_sesion = 'backend_kumbiaphp';

    public static function autenticar($user, $pass, $encriptar = TRUE)
    {
        $pass = $encriptar ? self::hash($pass) : $pass;
        $auth = new Auth('class: usuarios',
                        'login: ' . $user,
                        'clave: ' . $pass,
                        "activo: 1");
        if ($auth->authenticate()) {
            if (Input::post('recordar')) {
                self::setCookies($user, $pass);
            } else {
                self::deleteCookies();
            }
        }
        return self::estaLogueado();
    }

    public static function estaLogueado()
    {
        return Auth::is_valid();
    }

    public static function cerrarSesion()
    {
        Auth::destroy_identity();
        self::deleteCookies();
    }

}

