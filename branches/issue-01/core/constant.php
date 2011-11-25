<?php
/**
 * This file is part of Very Light MVC Framework
 * 
 * Very Light MVC Framework is free software: you can redistribute 
 * it and/or modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.

 * Very Light MVC Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with Very Light MVC Framework.  If not, 
 * see <http://www.gnu.org/licenses/>
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

//ONLY FOR INTERNAL USED
if (!defined("APP_CONFIG_FILE")) {
    define("APP_CONFIG_FILE", "");
}
define("ROOT_PATH", realpath(dirname(__FILE__)."/../")."/");
define("LOGGER_LEVEL_DEBUG", 1);
define("LOGGER_LEVEL_INFO", 2);
define("LOGGER_LEVEL_WARNING", 4);
define("LOGGER_LEVEL_ERROR", 8);
define("LOGGER_LEVEL_OFF", 16);
?>