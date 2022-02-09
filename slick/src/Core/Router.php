<?php
declare(strict_types=1);
namespace Slick\Core;

use Slick\Core\Request;
use Slick\Core\Dispacher;

/**
 * <b>Router</b>
 * 
 * Router is the class responsible for 
 * managing the routing system
 * 
 * @author Antoniel Bordin <antonielbordin@hotmail.com>
 * @copyright © 2019, Stilos Design
 * @version 2.0
 * @since release 2.0
 */
class Router extends Dispacher
{
  /**
   * store request data
   * 
   * @var Request
   * @access public
   */
  public $request;
  /**
   * <b>__construct</b>
   * 
   * __construct is the router class 
   * constructor method
   * 
   * @return void
   * @access public 
   */
  public function __construct(Request $request)
  {
    parent::__construct($request);
  }
  /**
   * <b>get</b> 
   * 
   * get is the method responsible for set get
   * request http method for route
   *
   * @param string $uri
   * @param object|callback $callback
   * @param mixed $namespace
   * @access public
   */
  public function get($uri, $callback, $namespace = null)
  {
    $this->addRoute('GET', $uri, $callback, $namespace);
  }
  /**
   * <b>post</b> 
   * 
   * post is the method responsible for set post
   * request http method for route
   *
   * @param string $uri
   * @param object|callback $callback
   * @param mixed $namespace
   * @access public
   */
  public function post($uri, $callback, $namespace = null)
  {
    $this->addRoute('POST', $uri, $callback, $namespace);
  }
  /**
   * <b>put</b> 
   * 
   * put is the method responsible for set put
   * request http method for route
   *
   * @param string $uri
   * @param object|callback $callback
   * @param mixed $namespace
   * @access public
   */
  public function put($uri, $callback, $namespace = null)
  {
    $this->addRoute('PUT', $uri, $callback, $namespace);
  }
  /**
   * <b>delete</b>
   * 
   * delete is the method responsible for set delete
   * request http method for route
   *
   * @param string $uri
   * @param object|callback $callback
   * @param mixed $namespace
   * @access public
   */
  public function delete($uri, $callback, $namespace = null)
  {
    $this->addRoute('DELETE', $uri, $callback, $namespace);
  }
  /**
   * <b>any</b>
   * 
   * any is the method responsible for set 
   * any request http method for route
   *
   * @param string $uri
   * @param object|callback $callback
   * @param mixed $namespace
   * @access public
   */
  public function any($uri, $callback, $namespace = null)
  {
    $this->addRoute('GET|POST', $uri, $callback, $namespace);
  }
  /**
   * <b>prefix<b>
   * 
   * prefix is the method responsible for 
   * ... 
   * 
   * @param string $prefix
   * @param callback $callback
   * @access public
   */
  public function prefix($prefix, $callback)
  {
    $parentPrefix = $this->prefix;
    $this->prefix .= '/' . trim($prefix, '/');
    if (is_callable($callback)) :
      call_user_func($callback);
    else :
      throw new \BadFunctionCallException(
        "Plase provide valid callback function!"
      );
    endif;
    $this->prefix = $parentPrefix;
  }
  /**
   * <b>middleware</b>
   * 
   * middleware is the method responsible for
   * actions middleware before render route
   * 
   * @param string $middleware
   * @param callback $callback
   * @access public
   */
  public function middleware($middleware, $callback)
  {
    $parentMiddleware = $this->middleware;
    $this->prefix .= '|' . trim($middleware, '|');
    if (is_callable($callback)) :
      call_user_func($callback);
    else :
      throw new \BadFunctionCallException(
        "Plase provide valid callback function!"
      );
    endif;
    $this->middleware = $parentMiddleware;
  }
}
