<?php
declare(strict_types=1);
namespace Slick\Helpers\DataLayer;

use PDO;
/**
 * <b>Database</b>
 * 
 * Database is the class responsible for 
 * managing the database allowing connection 
 * with different types of database using PDO
 *
 * @author Antoniel Bordin <antonielbordin@hotmail.com>
 * @copyright © 2019, Stilos Design
 * @version 2.0
 * @since release 1.0
 */
class Database extends PDO {
    /**
     * Store string with PDO dsn data 
     * 
     * @var string $dsn
     * @access private
     */
    private $dsn;
    /**
     * Store string with PDO default dsn data
     * 
     * @var string $dsnDefault
     * @access private
     */
    private $dsnDefault;    
    /** */
    private $options;
    /**
     * Store the string with data of the charset 
     * types that will be used in PDO
     * 
     * @var string $charset
     * @access private
     */
    private $charset;    
    /**
     * <b>__construct</b>
     * 
     * __construct is the constructor method of the 
     * Database class and is responsible for setting 
     * data in the connection instance
     * 
     * @param string $host Stores the host address
     * @param string $dbname Store the name of the database
     * @param string $user Stores the database user
     * @param string $pass Store the database password
     * @param string $char
     * @param string $dbType Stores the database type
     * @return void
     * @access public
     */
    public function __construct($host, $dbname, $user, $pass, $char, $dbType = null)
    {
        // foreach(PDO::getAvailableDrivers() as $driver) {
        //     echo $driver, '<br>';
        // }
        $this->dsnDefault = 'mysql:host='.$host.';dbname='.$dbname;
        $this->charset = $char;
        $this->dataBaseValidate($host, $dbname, $dbType);
        parent::__construct($this->dsn, $user, $pass, $this->options);                
    }
    /**
     * <b>dataBaseValidate</b>
     * 
     * dataBaseValidate is the method responsible for 
     * validating the data that will be used for the 
     * database in the PDO
     * 
     * @param type $host Stores the host address 
     * @param type $dbname Store the name of the database
     * @param type $dbType Stores the database type
     * @return void
     * @access private
     */
    private function dataBaseValidate($host, $dbname, $dbType)
    {        
        if($dbType != null):
            if(is_int($dbType)):
                $this->dataBaseManager($host, $dbname, $dbType);                
            else:
                $this->dataBaseManager($host, $dbname, 0);
            endif;
        else:
           $this->dataBaseManager($host, $dbname, 0);
        endif;
    }
    /**
     * <b>dataBaseManager</b>
     * 
     * dataBaseManager is the method responsible for 
     * managing the database that will be used in the 
     * PDO Allowed values ​​in integers:
     * 
     * [1] MySQL
     * [2] PostgreSQL
     * [3] . . .
     * 
     * The default default is MySQL
     * 
     * @param type $host Stores the host address 
     * @param type $dbname Store the name of the database
     * @param type $dbType Stores the database type
     * @return void
     * @access private
     */
    private function dataBaseManager($host, $dbname, $dbType){
        switch ($dbType) :
            case 1 :
                $this->dsn = 'mysql:host='.$host.';dbname='.$dbname;
                $this->options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.$this->charset);
                break;
            case 2 :
                $this->dsn = 'pgsql:host='.$host.';dbname='.$dbname; 
                $this->options = array();
                break;
            case 3 :
                // $tns = " (DESCRIPTION =(ADDRESS =(PROTOCOL = TCP)(HOST = $host)(PORT = 1521))(CONNECT_DATA = (SID = $dbname)))";
                /* $tns = " (DESCRIPTION =(ADDRESS_LIST =(ADDRESS = (PROTOCOL = TCP) (HOST = ".$host.")(PORT = 1521)))(CONNECT_DATA = (SID = ".$dbname.")))";*/
                // $this->dsn = 'oci:dbname='.$tns; 
                $this->dsn = "oci:dbname=//$host:1521/$dbname";
                $this->options = array();
                break;
            default :
                $this->dsn = 'mysql:host='.$host.';dbname='.$dbname;
                $this->options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.$this->charset);
                break;
        endswitch;        
    }
}
