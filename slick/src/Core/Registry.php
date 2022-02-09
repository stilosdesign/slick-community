<?php
declare(strict_types=1);
namespace Slick\Core;

/**
 * <b>Registry</b>
 * 
 * Registry is the class responsible for registering 
 * the instances of system classes providing the singleton 
 * standard thus ensuring only a single global instance of 
 * the registered classes
 *
 * @author Antoniel Bordin <antonielbordin@hotmail.com>
 * @copyright © 2019, Stilos Design
 * @version 2.0
 * @since release 1.0
 */
class Registry {
    /**
     * Static variable that stores the instances
     * of a certain class ie [ objects ]
     * 
     * @var object $instance
     * @access private
     */
    private static $instance;
    /**
     * Variable responsible for Amazon temporary 
     * data within the class
     * 
     * @var string $data
     * @access private 
     */
    private $data;
    /**
     * <b>__construct()</b>
     * 
     * __construct() is the constructor method of
     * Registry class and is of type private so that 
     * this class is not instantiated
     * 
     * @return void
     * @access private
     */
    private function __construct() {} 
    /**
     * <b>getInstance</b>
     * 
     * getInstance is the method responsible for return an 
     * existing instance of a system class using the [singleton] 
     * pattern as a metric to not allow multiple instances of the 
     * same class. If an instance already exists, this method 
     * returns the same, otherwise a new instance is created.
     * 
     * @return object
     * @access public
     */
    public static function getInstance() 
    {
        if (!self::$instance instanceof self) :
            self::$instance = new Registry();
        endif;   
        return self::$instance;
    }
    /**
     * <b>__set</b>
     * 
     * __set is a magic method responsible
     * for setting data
     * 
     * @param string $name
     * @param string $value
     * @return void
     * @access public
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
    /**
     * <b>__get</b>
     * 
     * __get is a magic method responsible
     * for return data
     * 
     * @param string $name
     * @return boolean|string
     * @access public
     */    
    public function __get($name){
        if (isset($this->data[$name])) :
            return $this->data[$name];
        endif;        
        return false;
    }
}