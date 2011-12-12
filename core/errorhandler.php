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

/**
 * Class ErrorHandler
 * 
 * Is a singleton used to handler the errors
 *
 * @category  FrontEnd
 * @package   VLMVC
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2011 Ignacio R. Galieri
 * @license   GNU GPL v3
 * @link      http://ar.linkedin.com/pub/ignacio-rodrigo-galieri/a/22/bb2
 */
class ErrorHandler
{
    static private $_instance = null;
    
    /**
     * Constructor
     * 
     * @return ErrorHandler
     */
    private function __construct()
    {
        set_error_handler(array($this, 'handler'));
    }
    
    /**
     * Error Handler
     *
     * @param String $type    Type
     * @param String $error   Error
     * @param String $file    File
     * @param String $line    Line
     * @param String $context Context
     *
     * @return void
     */
    public function handler($type, $error, $file, $line, $context)
    {
        $controller = getInstance();

        switch( $type ) {
        case E_WARNING:
            $errorType = 'SYSTEM WARNING';
            $errorDescription = $error;
            break;
        case E_NOTICE:
            $errorType = 'SYSTEM NOTICE';
            $errorDescription = $error;
            break;
        case E_USER_ERROR:
            $errorType = "APPLICATION ERROR";
            $errorDescription = $error;
            break;
        case E_USER_WARNING:
            $errorType = "APPLICATION WARNING";
            $errorDescription = $error;
            break;
        case E_USER_NOTICE:
            //used for debugging
            $errorType = 'DEBUG';
            $errorDescription = $error;
            break;
        default:
            if (defined("E_DEPRECATED")) {
                if ($type == E_DEPRECATED) {
                    //para no tenr problemas con la compatibilidad
                    $errorType = 'PHP DEPRECATED';
                    $errorDescription = $error;
                } else {
                    //shouldn't happen, just display the error just in case
                    $errorType = 'UNKNOWN';
                    $errorDescription = $error;
                }
            } else {
                //shouldn't happen, just display the error just in case
                $errorType = 'UNKNOWN';
                $errorDescription = $error;
            }
        }

        if ($errorDescription != "") {
            $controller->loadLibrary("Logger");
            $controller->logger->logError(
                "ERROR TYPE ::".$errorType." Set logger in debug for more info."
            );
            $controller->logger->logDebug(
                "FILE::".$file." LINE::".$line." DESC::".$errorDescription
            );
            $controller->setErrorMessage($errorDescription);
        }
    }
    
    /**
     * Register Error Handler
     * 
     * @return void
     */
    static public function register()
    {
        if (self::$_instance == null) {
            self::$_instance = new ErrorHandler();
        }
    }
}
?>