<?php
namespace Slick\Helpers\Shield;

/**
 * <b>Session</b>
 * 
 * Session is the class responsible for
 * managing the system sections
 *
 * @author Antoniel Bordin <antonielbordin@hotmail.com>
 * @copyright © 2019, Stilos Design
 * @version 2.0
 * @since release 1.0
 */
class Session {  
    /**
     * <b>init</b>
     * 
     * init is the method responsible for 
     * initializing the section
     * 
     * @return void
     * @access public
     */
    public static function init()
    {
        session_start();
    }     
    /**
     * <b>destroy</b> 
     * 
     * destroy is the method responsible for 
     * destroying a given section
     * 
     * @param boolean $key
     * @return void
     * @access public 
     */
    public static function destroy($key = false)
    {
        if ($key) :
            if (is_array($key)) :
                for ($i = 0; $i < count($key); $i++) :
                    if (isset($_SESSION[$key[$i]])) :
                        unset($_SESSION[$key[$i]]);
                    endif;
                endfor;
            else :
                if (isset($_SESSION[$key])) :
                    unset($_SESSION[$key]);
                endif;
            endif;
        else :
            session_destroy();
        endif;
    }    
    /**
     * <b>set</b> 
     * 
     * set is the method responsible for adding value 
     * in the section of type: key = value
     * 
     * @param string $key
     * @param string $value
     * @return void
     * @access public
     */
    public static function set($key, $value)
    {
        if (!empty($key)) :
            $_SESSION[$key] = $value;
        endif;
    }   
    /**
     * <b>setValue</b> 
     * 
     * setValue is the method responsible for adding
     * value for the key with an index of type:
     * $key[$index] = $value
     * 
     * @param string $key
     * @param string $index
     * @param string $value
     * @return void
     * @access public
     * @since Release 2.0
     */
    public static function setValue($key, $index, $value)
    {
        if (!empty($key) && !empty($index)) :
            $_SESSION[$key][$index] = $value;
        endif;
    } 
    /**
     * <b>get</b> 
     * 
     * get is the method responsible for 
     * returning a section variable 
     * 
     * @param string $key
     * @return array
     * @access public
     */
    public static function get($key)
    {
        if (isset($_SESSION[$key])) :
            return $_SESSION[$key];
        endif;
    }
    /**
     * <b>getKey</b> 
     * 
     * getKey is the method responsible for returning a 
     * value within a section variable indicated by 
     * $sessionName and the passed $key
     * 
     * @param string $sessionName
     * @param string $key
     * @return string
     * @access public
     */
    public static function getKey($sessionName, $key)
    {
        if (isset($_SESSION[$sessionName])) :
            return $_SESSION[$sessionName][$key];
        endif;
    } 
    /**
     * <b>accessTime</b> 
     * 
     * accessTime is the method responsible for checking 
     * and changing the time of the sections
     * 
     * @throws Exception
     * @return void
     * @access public
     */
    public static function accessTime()
    {
        if (!Session::get('timeSession') || !defined('SESSION_TIME')) :
            throw new \Exception('No set time Session!'); 
        endif;        
        if (SESSION_TIME == 0) :
            return;
        endif;        
        if (time() - Session::get('timeSession') > (SESSION_TIME * 60)) :
            Session::destroy();
        else :
            Session::set('timeSession', time());
        endif;
    }
}