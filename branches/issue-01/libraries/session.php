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

/**
 * Class Session 
 *
 * @package   VLMVC
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2011 Ignacio R. Galieri
 * @link      http://ar.linkedin.com/pub/ignacio-rodrigo-galieri/a/22/bb2
 */
class Session
{
    private $_token = "";
    private $_privateKey = "";
    
    /**
     * Class Constructor
     * 
     * @param string $privateKey Private Key
     * 
     * @return Session
     */
    public function __construct($privateKey)
    {
        $this->_privateKey = $privateKey;
        
        if (session_id() == "") {
            session_start();
        }
    }
    
    /**
     * Return the Session Array Data saved in Varible Name
     *
     * @param String $name Variable Name
     * 
     * @return mixed 
     */
    public function __get($name)
    {
        
        if (isset($_SESSION[$this->_getToken()][$name])) {
            return $_SESSION[$this->_getToken()][$name];
        }
        
        return null;
    }
    
    /**
     * Save The Variable in Session Array
     *
     * @param String $name  Variable Name
     * @param Mixed  $value Variable Value
     * 
     * @return void
     */
    public function __set($name, $value)
    {
        $_SESSION[$this->_getToken()][$name] = $value;
    }
    
    /**
     * Destroy de Current Session
     * 
     * @return void
     */
    public function destroySession(){
        unset($_SESSION[$this->_getToken()]);
    }
    
    /**
     * Return Token
     * 
     * @return String
     */
    private function _getToken()
    {
        if ($this->_token == "") {
            $this->_token = md5($this->_privateKey.$_SERVER["REMOTE_ADDR"]);
        }
        return $this->_token;
    }
}
?>
