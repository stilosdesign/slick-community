<?php
declare(strict_types=1);
namespace Slick\Settings;
/**
 * <b>Defines</b>
 * 
 * Defines is the class responsible for the definitions 
 * of global system constants  
 *
 * @author Antoniel Bordin <antonielbordin@hotmail.com>
 * @copyright © 2019, Stilos Design
 * @version 2.0
 * @since release 1.0
 */ 
class Defines
{
  /**
   * <b>init</b>
   * 
   * init is the method responsible for start 
   * defines in application
   * 
   * @return void
   * @access public
   */
  public static function init()
  {
    // TODO: Check if not defined yet
    // TODO: Get this data from an ini
    define('SLICK', ROUTE . 'slick' . DS . 'src' . DS);
    define('PATH_SETTINGS', SLICK . 'Settings' . DS);
    define('PATH_CORE', SLICK . 'Core' . DS);
    define('PATH_LIBS', SLICK . 'Libs' . DS);
    define('PATH_HELPERS', SLICK . 'Helpers' . DS);

    define('BASE_URL', 'http://webapi.dominio.com.br/');
    define('DEFAULT_LAYOUT', 'default');
    define('SESSION_TIME', 10);
    define('HASH_KEY', '4f6a6d832be79');

    define('DB_HOST', '000.000.000.000');
    define('DB_USER', 'user');
    define('DB_PASS', 'pass');
    define('DB_NAME', 'dbname');
    define('DB_CHAR', 'utf8');

    define('MAIL_HOST', 'host.dominio.com.br');
    define('MAIL_PORT', '587');
    define('MAIL_USER', 'mail@dominio.com.br');
    define('MAIL_PASS', 'pass');
    define('MAIL_CHAR', 'UTF-8');
  }
  public static function pathSettings(): string
  {
    return PATH_SETTINGS;
  }
  public static function pathCore(): string
  {
    return PATH_CORE;
  }
  public static function pathLibs(): string
  {
    return PATH_LIBS;
  }
  public static function pathHelpers(): string
  {
    return PATH_HELPERS;
  }
  public static function baseUrl(): string
  {
    return BASE_URL;
  }
  public static function route(): string
  {
    return ROUTE;
  }
  public static function ds(): string
  {
    return DS;
  } 
}
