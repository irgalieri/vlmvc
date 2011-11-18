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
 * Class Base Controller
 *
 * @package   VLMVC
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2011 Ignacio R. Galieri
 * @link      http://ar.linkedin.com/pub/ignacio-rodrigo-galieri/a/22/bb2
 */
class Controller
{
    private static $_instance = null;

    private $_module;
    private $_models = array();
    private $_post = array();
    private $_libraries = array();
    private $_lastError = "";

    /**
     * Return Module String
     *
     * @return String
     */
    public function getModule()
    {
        return $this->_module;
    }

    /**
     * Set Module String
     *
     * @param String $module Module Name
     *
     * @return void
     */
    public function setModule($module)
    {
        $this->_module= $module;
    }

    /**
     * Find and Include View File
     *
     * @param String $name   view name (without php)
     * @param Array  $params parameter to pass to the view
     *
     * @return void
     */
    public function loadView($name = "",$params = array())
    {
        $name = strtolower($name);

        $file_view = $this->_loadFile($name, "views");

        if ($file_view == "") {
            $this->message(1, base64_encode($this->getErrorMessage()));
            die;
        }

        extract($params);
        
        include_once $file_view;
    }

    /**
     * Find and Include Model Class
     *
     * @param String $name   Model Class Name (without php and camel case)
     * @param Array  $params parameter to pass to the models contructor
     *
     * @return boolean
     */
    public function loadModel($name = "",$params = array())
    {
        $name_variable = strtolower($name);

        if (isset($this->_models[$name_variable])) {
            return true;
        }

        $file_model = $this->_loadFile($name_variable, "models");

        if ($file_model == "") {
            return false;
        }

        include_once $file_model;

        switch (count($params)){
        case 0:
            $this->_models[$name_variable] = new $name();
            break;
        case 1:
            $this->_models[$name_variable] = new $name($params[0]);
            break;
        case 2:
            $this->_models[$name_variable] = new $name($params[0],$params[1]);
            break;
        case 3:
            $this->_models[$name_variable] = new $name($params[0],$params[1],$params[2]);
            break;
        default :
            $reflection = new ReflectionClass($name);
            $this->_models[$name_variable] = $reflection->newInstanceArgs($params);
            break;
        }

        return true;
    }
    
    /**
     * Find and Include Library Class
     *
     * @param String $name   Library Class Name (without php and camel case)
     * @param Array  $params parameter to pass to the library contructor
     *
     * @return boolean
     */    
    public function loadLibrary($name = "", $params = array())
    {
        $name_variable = strtolower($name);
        
        if (isset($this->_libraries[$name_variable])) {
            return true;
        }
        
        $file_lib = $this->_loadFile($name_variable, "libraries"); 
        
        if ($file_lib == "") {
            return false;
        }
        
        include_once $file_lib;
        
        switch (count($params)){
        case 0:
            $this->_libraries[$name_variable] = new $name();
            break;
        case 1:
            $this->_libraries[$name_variable] = new $name($params[0]);
            break;
        case 2:
            $this->_libraries[$name_variable] = new $name($params[0],$params[1]);
            break;
        case 3:
            $this->_libraries[$name_variable] = new $name($params[0],$params[1],$params[2]);
            break;
        default :
            $reflection = new ReflectionClass($name);
            $this->_libraries[$name_variable] = $reflection->newInstanceArgs($params);
            break;
        }

        return true;        
    }

    /**
     * Find a File in itms directory
     *
     * @param String $file File name [without php]
     * @param String $type File type [models|views|libraries]
     *
     * @return String
     */
    private function _loadFile($file = "", $type = "views")
    {

        $filePath =$file.".php";

        $fileCore = ROOT_PATH.$type."/".$filePath;
        $fileModule = APP_PATH."modules/".$this->getModule()."/".$type."/".$filePath;
        $fileProject = APP_PATH.$type."/".$filePath;

        if (is_file($fileModule)) {
            return $fileModule;
        } elseif (is_file($fileProject)) {
            return $fileProject;
        } elseif (is_file($fileCore)) {
            return $fileCore;
        } else {
            $this->setErrorMessage("Unable to load the file ".$filePath);
            $this->loadLibrary("logger");
            $this->logger->logError("The file does not exist. Set Logger in the debug mode to more detail.");
            $this->logger->logDebug("File name: ".$filePath." Type File: ".$type);
        }

        return '';
    }

    /**
     * Return the instance of Model
     *
     * @param String $name name of model (lower case)
     *
     * @return Model
     */
    public function __get($name)
    {
        if (isset($this->_models[$name])) {
            return $this->_models[$name];
        } else if (isset($this->_libraries[$name])) {
            return $this->_libraries[$name];
        }

        return null;
    }

    /**
     * Return Value of Post Index
     *
     * @param String $name    Name Variable
     * @param mixed  $default default to return
     *
     * @return String
     */
    public function getPost($name = "", $default = "")
    {
        if (isset($this->_post[$name])) {
            return $this->_post[$name];
        }

        return $default;
    }

    /**
     * Set post Values
     *
     * @param <array $post $_POST
     *
     * @return void
     */
    public function setPost($post =array())
    {
        $this->_post = $post;
    }

    /**
     * Redirect URL
     *
     * @param String $uri                URI
     * @param String $method             Method
     * @param String $http_response_code Http Responce Code
     *
     * @return void
     */
    function redirect($uri = '', $method = 'location', $http_response_code = 302)
    {
        if (!preg_match('#^https?://#i', $uri)) {
            $uri = BASE_URL.$uri;
        }

        switch ($method) {
            case 'refresh' :
                header("Refresh:0;url=" . $uri);
                break;
            default :
                header("Location: " . $uri, true, $http_response_code);
                break;
        }
        exit;
    }
    
    /**
     * Contructor
     * 
     * @return Controller
     */
    public function __construct() 
    {
        self::$_instance =& $this;
    }
    /**
     * Return Instance
     *
     * @return Controller
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            new Controller();
        }
        
        return self::$_instance;
    }

    /**
     * Has Error
     *
     * @return Boolean
     */
    public function hasError()
    {
        return ($this->_lastError != "");
    }

    /**
     * Return Last Error
     *
     * @return String
     */
    public function getErrorMessage()
    {
        return $this->_lastError;
    }

    /**
     * Set Last Error
     *
     * @param String $message Message
     *
     * @return void
     */
    public function setErrorMessage($message = "")
    {
        $this->_lastError = $message;
    }
    
    /**
     * Show Message
     *
     * @param integer $codError Error Code
     * @param string  $message  Message
     * 
     * @return void
     */
    public function message($codError = 0, $message ="En Construccion")
    {        
        $this->loadView("error_page", array ("code" => $codError,"message" => base64_decode($message)));
    }
}
?>
