<?php
declare(strict_types=1);
namespace Slick\Core;

use Slick\Core\Registry;
use Slick\Core\Request;
use Slick\Core\Router;
use Slick\Settings\Defines;
use Slick\Helpers\Shield\Session;
use Slick\Helpers\DataLayer\Database;

/**
 * <b>App</b>
 * 
 * App is the class responsible for starting 
 * the application
 * 
 * @author Antoniel Bordin <antonielbordin@hotmail.com>
 * @copyright © 2019, Stilos Design
 * @version 2.0
 * @since release 2.0
 */
class App {
  /**
   * <b>start</b>
   * 
   * start is the method responsible for 
   * start application
   * 
   * @return void
   * @access public 
   */
  public static function start() {    
    try {        
      Session::init();
      $registry = Registry::getInstance();
      $registry->request = new Request();
      $registry->response = new Response();      
      $registry->db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_CHAR, 3);
      // var_dump($registry->db);  

      $registry->response->setHeader('Access-Control-Allow-Origin: *');
      $registry->response->setHeader("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
      $registry->response->setHeader('Content-Type: application/json; charset=UTF-8');

      /** Create an instance of the router */
      $router = new Router($registry->request);

      /** Includes the routes mapped on the router */
      require_once (Defines::pathSettings() . 'Routes.php');

      /** Renders the petition if it is added to the routes */
      $router->run();

      /** Response Render Content */
      $registry->response->render();

    } catch(\Exception $ex) {
      // TODO: Work a log system
      echo 'Msg Erro regular: ' . $ex->getMessage(); 
    } catch (\PDOException $ex) {
      // TODO: Work a log system
      echo 'Msg Erro PDO: ' . $ex->getMessage();
    }
  }
}


