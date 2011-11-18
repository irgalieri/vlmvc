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
 * along with Foobar.  If not, see <http://www.gnu.org/licenses/>
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

// IWAY MVC ONLY
define("ROOT_PATH", realpath(dirname(__FILE__)."/../")."/");

define("LOGGER_LEVEL_DEBUG"  , 1);
define("LOGGER_LEVEL_INFO"   , 2);
define("LOGGER_LEVEL_WARNING", 4);
define("LOGGER_LEVEL_ERROR"  , 8);
define("LOGGER_LEVEL_OFF"    , 16);


// FOLDERS
if (!defined("APP_PATH")) {
    define("APP_PATH", realpath(dirname(__FILE__)."/../")."/");
}

// System URL
if (!defined("BASE_URL")) {
    define("BASE_URL", "http://".$_SERVER["SERVER_NAME"]."/vlmvc/");
}

if (!defined("FIRST_MODULE")) {
    define("FIRST_MODULE", "home");
}

if (!defined("FIRST_CONTROLLER")) {
    define("FIRST_CONTROLLER", "home");
}


// DB Config
if (!defined("USERNAME")) {
    define("USERNAME", "");
}

if (!defined("PWD")) {
    define("PWD", "");
}

if (!defined("DBNAME")) {
    define("DBNAME", "");
}

if (!defined("HOST")) {
    define("HOST", "");
}

if (!defined("TYPE")) {
    define("TYPE", "mysql");
}


// Logger
if (!defined("LOGGER_FILE")) {
    define("LOGGER_FILE", "/tmp/vlmvc");
}

if (!defined("LOGGER_MODULE")) {
    define("LOGGER_MODULE", "VLMVC");
}

if (!defined("LOGGER_FILE_QTY")) {
    define("LOGGER_FILE_QTY", 4);
}

if (!defined("LOGGER_LEVEL")) {
    define("LOGGER_LEVEL", "OFF");
}

if (!defined("APP_KEY")) {
    define("APP_KEY", "609c3c4b67a3bd22c47a66d0f0d46d02");
}
?>
