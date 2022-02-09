<?php
declare(strict_types=1);
namespace Slick\Core;

use Exception;
use Slick\Settings\Defines;
use App\Models as Model;

/**
 * <b>Controller</b>
 *
 * Controller class is a base class for system 
 * controllers providing data validation methods and 
 * other important resources. Ideally, system controllers 
 * generally inherit from this class
 * 
 * @author Antoniel Bordin <antonielbordin@hotmail.com>
 * @copyright © 2019, Stilos Design
 * @version 2.0
 * @since release 1.0
 */
abstract class Controller {
  /**
   * <b>index</b>
   * 
   * index is the method initial of the Controller 
   * class all children who inherit from this class 
   * must implement this method
   *  
   * @return void
   * @access public
   */
  abstract public function index();
  /**
   * <b>loadModel</b>
   * 
   * loadModel is the method responsible for 
   * loading a given model passed as a parameter 
   * by $model
   *  
   * @param string $model
   * @return object
   * @throws Exception
   * @access protected
   */    
  protected function loadModel($model)
  {
    $className = $model.'Model';
    $model = 'App\\Models\\'.$className;
    $routeModel = Defines::route().'app'.Defines::ds().'Models'.Defines::ds().$className.'.php'; 
        
    if (is_readable($routeModel)) {
      require_once $routeModel;
      $newModel = new $model;
      return $newModel;
    } else {
      throw new Exception('Error: Model Not Found');
    }
  }   
  /**
   * <b>getLibrary</b>
   * 
   * getLibrary is the method responsible 
   * for loading a library external to the 
   * application core
   *  
   * @param string $library
   * @throws Exception
   * @return void
   * @access protected
   */
  protected function getLibrary($library)
  {
    $routeLibrary = Defines::pathLibs().$library.'.php';        
    if(is_readable($routeLibrary)):
      require_once $routeLibrary;
    else:
      throw new Exception('Error: Library Not Found');
    endif;
  }  
  /**
   * <b>setUri</b>
   * 
   * setUri is the method responsible for transforming a 
   * past string as a parameter into a valid URL pattern, 
   * suitable for SEO removing accents and other unwanted 
   * characters 
   *  
   * @param string $url
   * @return string
   * @access public
   */
  public function setUri($url)
  {
    $a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
    $b = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';	
    $url = utf8_decode($url);
    $url = strtr($url, utf8_decode($a), $b);
    $url = strip_tags(trim($url));
    $url = str_replace(" ","-",$url);
    $url = str_replace(array("-----","----","---","--"),"-",$url);
    return strtolower(utf8_encode($url));
  } 
}