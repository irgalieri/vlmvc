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

/**
 * Class Config
 * 
 * Is a singleton used to manager the config
 *
 * @package   VLMVC
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2011 Ignacio R. Galieri
 * @link      http://ar.linkedin.com/pub/ignacio-rodrigo-galieri/a/22/bb2
 */
class Config
{
    static private $_intances = null;
    private $_configs = array();
    
    /**
     * Constructor
     * 
     * @return Config
     */
    private function __construct($configFile)
    {
        if (file_exists($configFile)) {
            $this->_configs = parse_ini_file($configFile, true);
        }
        
        $this->_configs += parse_ini_file(ROOT_PATH."config/config_default.ini", true);
    }
    
    /**
     * Return the current instance or instance a new one, 
     * if you no defined the config file, load the default config.
     * 
     * @param String $config_file Config File
     *
     * @return Config
     */
    static public function getInstance($configFile = "")
    {
        if (is_null(self::$_intances)) {
            self::$_intances = new Configurator($configFile);
        }
        
        return self::$_intances;
    }
    
    /**
     * Return the config property
     *
     * @param String $name Name property
     * 
     * @return mixed 
     */
    public function __get($name)
    {
        if (isset ($this->_configs[$name])) {
            return array2Object($this->_configs[$name]);
        }
        
        return "";
    }
}
?>
