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
 * @author    German Nemes <german.nemes@intraway.com>
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2011 Ignacio R. Galieri
 * @license   GNU GPL v3
 * @link      http://ar.linkedin.com/pub/ignacio-rodrigo-galieri/a/22/bb2
 */

/**
 * Class Logger
 *
 * @package   VLMVC
 * @author    German Nemes <gnemes@gmail.com>
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2011 Ignacio R. Galieri
 * @link      http://ar.linkedin.com/pub/ignacio-rodrigo-galieri/a/22/bb2
 */
class Logger
{
    private $_filelogging_format = '%TIMESTAMP% [%MODULE%.%LOGTYPE%] %DATA%';
    private $_timestamp_format   = "Y.m.d H:i:s";
    private $_module             = LOGGER_MODULE;
    
    // Log file without .log extension, it is added later
    private $_logfile            = LOGGER_FILE;    
    
    // Quantity of files saved
    private $_historyQty         = LOGGER_FILE_QTY;
    
    private $_loggerLevel = LOGGER_LEVEL_INFO;
    
    /**
     * Logger constructor
     *
     * @return Void
     */
    public function __construct()
    {
        switch(LOGGER_LEVEL) {
        case "DEBUG":
            $this->_loggerLevel = LOGGER_LEVEL_DEBUG;
            break;
        case "INFO":
            $this->_loggerLevel = LOGGER_LEVEL_INFO;
            break;
        case "WARNING":
            $this->_loggerLevel = LOGGER_LEVEL_WARNING;
            break;
        case "ERROR":
            $this->_loggerLevel = LOGGER_LEVEL_ERROR;
            break;
        case "OFF":
        default :
            $this->_loggerLevel = LOGGER_LEVEL_OFF;
            break;
        }
    }
    
    /**
     * Puts the log string into the file
     * 
     * @param string $sLogType Log Type
     * @param string $sStr     String to log 
     * 
     * @return void
     */
    private function _putLog($sLogType, $sStr)
    {
        if($this->_logfile != "" && !is_null($this->_logfile)) {	
            $this->_rotateLogFile();
            $data = $this->_formatMessage($sLogType, $sStr);
            $this->_writeToFile($data);
        }
    }
    
    /**
     * Write message to file
     *
     * @param string $data Message formated
     * 
     * @return void
     */
    private function _writeToFile($data) {
        error_log( $data . "\n", 3, $this->_logfile.".log");
    }

    /**
     * Format message
     *
     * @param string $sLogType Logger Type
     * @param string $sStr     Message
     * 
     * @return string 
     */
    private function _formatMessage($sLogType, $sStr) {
        $data = $this->_filelogging_format;
        $data = str_replace("%TIMESTAMP%", date($this->_timestamp_format), $data);
        $data = str_replace("%MODULE%", $this->_module, $data);
        $data = str_replace("%LOGTYPE%", $sLogType, $data);
        $data = str_replace("%DATA%", $sStr, $data);    
        
        return $data;
    }

    /**
     * Rotate log files
     * 
     * @return void
     */
    private function _rotateLogFile() {
        if (file_exists($this->_logfile.".log")) {
            // Checks if the filesize is larger than 1gb, if it is so, rotate files
            if (filesize($this->_logfile.".log")/1024/1024/1024 > 1) {
                for($i = $this->_historyQty; $i > 0; $i--) {
                    if (file_exists($this->_logfile."_".$i.".log")) {
                        if ($i != $this->_historyQty) {
                            $j = $i + 1;
                            copy($this->_logfile."_".$i.".log", $this->_logfile."_".$j.".log");
                        }
                        unlink($this->_logfile."_".$i.".log");
                    }
                }
                copy($this->_logfile.".log", $this->_logfile."_1.log");
                unlink($this->_logfile.".log");
            }
        }
    }

    /**
     * Log debug
     *
     * @param string $sStr Message
     * 
     * @return void
     */
    public function logDebug($sStr = '')
    {
        if ($this->_loggerLevel <= LOGGER_LEVEL_DEBUG) {
            $this->_putLog('DEBUG',$sStr);
        }
    }
    
    /**
     * Log info
     *
     * @param string $sStr Message
     * 
     * @return void
     */
    public function logInfo($sStr = '')
    {
        if ($this->_loggerLevel <= LOGGER_LEVEL_INFO) {
            $this->_putLog('INFO',$sStr);
        }
    }    
    
    /**
     * Log warning
     *
     * @param string $sStr Message
     * 
     * @return void
     */
    public function logWarning($sStr = '')
    {
        if ($this->_loggerLevel <= LOGGER_LEVEL_WARNING) {
            $this->_putLog('WARNING', $sStr);
        }
    }    

    /**
     * Log error
     *
     * @param string $sStr Message
     * 
     * @return void
     */
    public function logError($sStr = '')
    {
        if ($this->_loggerLevel <= LOGGER_LEVEL_ERROR) {
            $this->_putLog('ERROR', $sStr);
        }
    }

}
?>
