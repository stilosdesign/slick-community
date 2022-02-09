<?php
declare(strict_types=1);
namespace Slick\Helpers\DataLayer;

use PDO;
use PDOException;
use Exception;
use Slick\Core\Registry;
/**
 * <b>DataLayer</b>
 * 
 * DataLayer is the class responsible for managing 
 * the models and data in the database allowing 
 * a dynamic crud
 *
 * @author Antoniel Bordin <antonielbordin@hotmail.com>
 * @copyright © 2019, Stilos Design
 * @version 2.0
 * @since release 1.0
 */ 
class DataLayer {
    /**
     * Store an instance of a certain class 
     * 
     * @var object $registry
     * @access private
     */
    private $registry;
    /**
     * Store database connection
     * 
     * @var string $db 
     * @access protected
     */
    protected $db;   
    /**
     * Store the database table that 
     * will be manipulated
     * 
     * @var string $table
     * @access protected 
     */
    protected $table;  
    /**
     * Store the results returned from 
     * the database
     * 
     * @var int $result
     * @access protected
     */
    protected $result;
    /**
     * Store the amount of affected 
     * results
     * 
     * @var int $countResult
     * @access protected
     */
    protected $countResult;
    /**
     * <b>__construct</b>
     * 
     * __construct is the constructor method of 
     * the DataLayer class which is responsible
     * for loading or creating an instance of the 
     * database in the system
     * 
     * @return void
     * @access public
     */
    public function __construct() {
        $this->registry = Registry::getInstance();
        $this->db = $this->registry->db;
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } 
    /**
     * <b>create</b>
     * 
     * create is the method responsible for entering 
     * data in the database
     * 
     * @param string $table
     * @param array $data
     * @throws Exception
     * @return void
     * @access public
     */
    public function create($table, Array $data) { 
        $newData = $data;
        $fields = implode(", ", array_keys($newData)); 
        $places = ":".implode(", :", array_keys($newData));         
        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$places})";
        
		try {
            $stmt = $this->db->prepare($sql); 
            $stmt->execute($data);
            $this->result = $this->db->lastInsertId();
        } catch (PDOException $e) { 
            $this->result = NULL;
            throw new Exception('<b>Error: </b>'.$e->getMessage());            
        }		
    }
    /**  
     * <b>read</b>
     * 
     * read is the method responsible for reading the data 
     * from the database according to the table and conditions 
     * informed returning an associative array
     *  
     * @param string $table Stores table name in database
     * @param string $condition Allows adding query conditions 
     * Example WHERE id = '1' By default the value is null   
     * @param boolean $op Indicates the return type
     * @return array 
     * @access public 
     */       
    public function read($table, $condition = NULL, $op = false){
        if($condition):
            $sql = "SELECT * FROM {$table} {$condition}";
        else:
            $sql = "SELECT * FROM {$table}";  
        endif;
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            if($op):
                $this->result = $stmt->fetch();
            else:
                $this->result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
            endif;
            $this->countResult = $stmt->rowCount();            
        } catch (PDOException $exc) {   
            $this->result = NULL;
            throw new Exception('<b>Error: </b>'.$exc->getMessage());            
        }  
    }
    /** 
     * <b>readAll</b>
     * 
     * readAll is the Method responsible for performing 
     * the total reading of data from a database table 
     * returning an associative array 
     *  
     * @param $table Stores table name in database
     * @return array 
     * @access public 
     */  
    public function readAll($table) {
        $sql = "SELECT * FROM {$table}";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $this->result = $stmt->fetchAll(PDO::FETCH_ASSOC);            
            $this->countResult = $stmt->rowCount(); 
        } catch (PDOException $exc) {
            $this->result = NULL;
            throw new Exception('<b>Error: </b>'.$exc->getMessage());            
        }
    } 
    /**
     * <b>readQuery</b>
     *  
     * readQuery is the Method responsible for reading 
     * the data in the Database from an SQL statement 
     * passed as a parameter 
     *  
     * @param $query SQL statement to be executed
     * @param $op Indicates the return type
     * @return array 
     * @access public 
     */  
    public function readQuery($query, $op = false){
        $sql = $query;
         try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            if($op):
                $this->result = $stmt->fetch();
            else:
                $this->result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
            endif;                       
            $this->countResult = $stmt->rowCount(); 
        } catch (PDOException $exc) {
            $this->result = NULL;
            throw new Exception('<b>Error: </b>'.$exc->getMessage());            
        }        
    }     
    /**  
     * <b>update</b>
     * 
     * update is the method responsible for updating the 
     * data in a given database table
     *   
     * @param $table Stores table name in database
     * @param $data Data array containing columns and values  
     * @param $where Condition array for WHERE statement Example (id = '1')   
     * @return boolean 
     * @access public 
     */   
    public function update($table, Array $data, $where){
        $newData = $data;        
        foreach ($newData as $fields => $value) :
            $newFields[] = "{$fields} = {$value}";
        endforeach;

        $newFields = implode(", ", $newFields);       
        $sql = "UPDATE  {$table} SET ".$newFields." WHERE ".$where;
		
        try {
            $stmt = $this->db->prepare($sql);            
            foreach ($data as $key => $values) :  
                $stmt->bindValue(':'.$key, $values);                 
            endforeach;             
            $stmt->execute();
        } catch (PDOException $exc) {   
            throw new Exception('<b>Error: </b>'.$exc->getMessage());            
        } 
    }
    /**  
     * <b>delete</b>
     * 
     * delete is the method responsible for deleting the 
     * data in a given database table 
     *   
     * @param $table Stores table name in database
     * @param $where Condition array for WHERE statement Example (id = '1')  
     * @return boolean
     * @access public 
     */ 
    public function delete($table, $where){
        $sql = "DELETE FROM {$table} WHERE ".$where;       
        try {
            $stmt = $this->db->prepare($sql);            
            $stmt->execute();
        } catch (PDOException $exc) {   
            throw new Exception('<b>Error: </b>'.$exc->getMessage());            
        }       
    }   
    /**  
     * <b>execQuery</b>
     * 
     * execQuery is the method responsible for executing 
     * a given query in the Database
     *   
     * @param $query Query for execution 
     * @return boolean 
     * @access public 
     */ 
    public function execQuery($query) {
        $sql = $query;
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        } catch (PDOException $exc) {
            throw new Exception('<b>Error: </b>'.$exc->getMessage());
        }
    }	
    /**
     * <b>getResult</b>
     * 
     * getResult is the Method responsible for returning the 
     * results stored in $this->result
     * 
     * @return array 
     * @access public
     */
    public function getResult(){
        return $this->result;
    }
    /**
     * <b>getRowCount</b>
     * 
     * getRowCount is the method responsible for returning 
     * the results stored in $this->countResult
     * 
     * @return int 
     * @access public
     */
    public function getRowCount(){
        return $this->countResult; 
    }
}