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
 * Base Model
 *
 * @category  FrontEnd
 * @package   VLMVC
 * @author    Ignacio R. Galieri <irgalieri@gmail.com>
 * @copyright 2011 Ignacio R. Galieri
 * @license   GNU GPL v3
 * @link      http://ar.linkedin.com/pub/ignacio-rodrigo-galieri/a/22/bb2
 */
abstract class Model
{
    
    private static $_db =  null;

    /**
     * Return The Instance Of DB
     *
     * @return PDO
     */
    protected function getDB()
    {
        $config = getConfig();
        
        if (is_null(self::$_db)) {
            $db = null;
            switch ($config->db->type) {
            case "mysql":
                $db = new PDO(
                    'mysql:host='.$config->db->host.';dbname='.$config->db->db_name, 
                    $config->db->username,
                    $config->db->password,
                    array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                    )
                );
                break;
            case "sqlite":
                $db = new PDO('sqlite:'.$config->db->db_name);
                break;
            case "none":
            default:
                break;
            }
            
            if (!is_null($db)) {
                $db->beginTransaction();
            }
            self::$_db = $db;
        }
        return self::$_db;
    }
    
    /**
     * Make a Query
     *
     * @param string $sql SQL
     * 
     * @return array
     */
    public function query($sql)
    {
        $statement = $this->_getDB()->query($sql);
        $errorInfo = $this->_getDB()->errorInfo();
        
        if (!empty ($errorInfo[1])) {
            getInstance()->setErrorMessage($errorInfo[2]);
            getInstance()->loadLibrary("Logger");
            getInstance()->logger->logError($errorInfo[2]);
        }
        
        return $statement->fetchAll();
    }
    
    /**
     * Excute a Query
     *
     * @param string $sql SQL
     * 
     * @return integer
     */
    public function exec($sql)
    {
        
        $result = $this->_getDB()->exec($sql);
        
        $errorInfo = $this->_getDB()->errorInfo();
        
        if (!empty ($errorInfo[1])) {
            getInstance()->setErrorMessage($errorInfo[2]);
            getInstance()->loadLibrary("Logger");
            getInstance()->logger->logError($errorInfo[2]);
        }
        
        return $result;
    }
    
    /**
     * Make a Query, but with paginate
     *
     * @param string  $sql        SQL
     * @param integer $pageNumber Page Number
     * @param integer $size       Size
     * 
     * @return array
     */
    public function page( $sql, $pageNumber = 1,$size = 10)
    {
        switch (TYPE) {
        case "mysql":
            $sqlTotal = <<<EOQ
SELECT count(1) as total
  FROM ({$sql}) a
EOQ;
            $result = $this->query($sqlTotal);
            $total = $result[0]["total"];

            if ($size == 0) {
                $size = $total;
            }

            if ($pageNumber <= 1) {
                $pageNumber = 1;
                $sqlPage = <<<EOQ
SELECT a.*
  FROM ({$sql}) a
 LIMIT 0, {$size}
EOQ;

                $sql .= " ";
            } else {
                $begin = ($pageNumber-1) * $size;
                $sqlPage = <<<EOQ
SELECT a.*
  FROM ({$sql}) a
 LIMIT {$begin}, {$size}
EOQ;
            }

            $result = $this->query($sqlPage);

            $data['records'] = $result;
            
            if (is_int($total / $size)) {
                $data['pages'] = $total / $size;
            } else {
                $data['pages'] = (int)(($total / $size) +1);
            }
            return $data;
            break;
        case "none":
            break;
        default:
            return $this->query($sql);
            break;
        }
    }

    /**
     * destructor
     *
     * @return void
     */
    public function  __destruct()
    {
        if (!is_null(self::$_db)) {
            $errorInfo = $this->_getDB()->errorInfo();
            if (is_null($errorInfo[1])) {
                $this->_getDB()->commit();
            } else {
                getInstance()->loadLibrary("Logger");
                getInstance()->logger->logError($errorInfo[2]);
                $this->_getDB()->rollBack();
            }
            self::$_db = null;
        }
    }
    
    /**
     * Force Commit
     * 
     * @return boolean
     */
    public function forceCommit()
    {
        $errorInfo = $this->_getDB()->errorInfo();
        if (is_null($errorInfo[1])) {
            $this->_getDB()->commit();
            return true;
        }
        
        getInstance()->loadLibrary("Logger");
        getInstance()->logger->logError($errorInfo[2]);
        $this->_getDB()->rollBack();

        return false;
    }
}
?>