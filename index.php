<?php
/**
 * This file is part of Very Light MVC Framework
 * 
 * Very Light MVC Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Very Light MVC Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with Very Light MVC Framework.  If not, see <http://www.gnu.org/licenses/>
 *
 * PHP VERSION 5
 *
 * @category  FrontEnd
 * @package   VLMVC
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2011 Ignacio R. Galieri
 * @license   GNU GPL v3
 * @link      http://ar.linkedin.com/pub/ignacio-rodrigo-galieri/a/22/bb2
 */

require_once 'core/constant.php';
require_once 'core/utils.php';
require_once 'core/config.php';
require_once 'core/errorhandler.php';
require_once 'core/controller.php';

ErrorHandler::register();

$config = Config::getInstance(APP_CONFIG_FILE);

$module = filter_input(INPUT_GET, "module", FILTER_SANITIZE_STRING);
$controller = filter_input(INPUT_GET, "controller", FILTER_SANITIZE_STRING);
$method = filter_input(INPUT_GET, "method", FILTER_SANITIZE_STRING);
$params = filter_input(INPUT_GET, "params", FILTER_SANITIZE_STRING);

if ($module == "") {
    $module = $config->urls->first_module;
    $controller = $config->urls->first_controller;
}

$controllerFile = $config->application->path;
$controllerFile .= "modules/".$module."/controllers/".$controller.".php";

if (is_file($controllerFile)) {
    include_once $controllerFile;
    
    $controller = str_replace("_", " ", $controller);
    $controller = ucwords($controller);
    $controller = str_replace(" ", "_", $controller);
    
    if (!empty ($params)) {
        $params = explode("/", $params);
    } else {
        $params = array();
    }
    
    if (class_exists($controller, false)) {
        $controllerClass = new $controller();
        $controllerClass->setModule($module);
        $controllerClass->setPost($_POST);

        if ($method == "") {
            $method = "index";
        }
        
        if (method_exists($controllerClass, $method)) {
            switch (count($params)){
            case 0:
                $controllerClass->{$method}();
                break;
            case 1:
                $controllerClass->{$method}($params[0]);
                break;
            case 2:
                $controllerClass->{$method}($params[0],$params[1]);
                break;
            case 3:
                $controllerClass->{$method}($params[0],$params[1],$params[2]);
                break;
            default :
                call_user_func_array(array($controllerClass, $method), $params);
                break;
            }
        } else {
            user_error("Don't exit method: ".$method, E_USER_ERROR);
        }
    } else {
        user_error("Don't exit class: ".$controller, E_USER_ERROR);
    }
} else {
    user_error("Don't exit file: ".$controllerFile, E_USER_ERROR);
}
?>
