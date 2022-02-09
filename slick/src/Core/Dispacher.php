<?php
declare(strict_types=1);
namespace Slick\Core;

use BadFunctionCallException;
use ReflectionException;

/**
 * <b>Dispacher</b>
 * 
 * Dispacher is the class responsible for
 * for handling request data validating url data, 
 * parameters, routes, prefix and middleware
 * 
 * @author Antoniel Bordin <antonielbordin@hotmail.com>
 * @copyright © 2019, Stilos Design
 * @version 2.0
 * @since release 1.0 
 */
abstract class Dispacher
{
  /**
   * Store request url
   *
   * @var string
   * @access protected
   */
  protected $url;
  /**
   * Store request prefix
   *
   * @var string
   * @access protected
   */
  protected $prefix;
  /**
   * Store request middleware
   *
   * @var string
   * @access protected
   */
  protected $middleware;
  /**
   * Store request routes
   *
   * @var array
   * @access protected
   */
  protected $routes = [];
  /**
   * Store request params
   *
   * @var array
   * @access protected
   */
  protected $params = [];
  /**
   * Store request http method
   *
   * @var string
   * @access protected
   */
  protected $method;
  /**
   * Store match route list
   *
   * @var array
   * @access private
   */
  private $matchRouter = [];
  /**
   * Allows these HTTP methods
   *
   * @var array
   * @access private
   */
  protected $httpMethods = ['GET', 'POST', 'DELETE', 'OPTION'];
  /**
   * Patterns that should be replaced
   *
   * @var array
   * @access private
   */
  private $patterns = [
    '~/~', // slash
    '~{[^\/{}]+}~', // normal placeholder
    '~{a:[^\/{}]+}~', // placeholder accepts only alphabetic chars
    '~{an:[^\/{}]+}~', // placeholder accepts alphabetic and numeric chars
    '~{n:[^\/{}]+}~', // placeholder accepts only numeric
    '~{w:[^\/{}]+}~', // placeholder accepts alphanumeric and underscore
    '~{\*:[^\/{}]+}~', // placeholder match rest of url
    '~(\\\/)?{\?:[^\/{}]+}~', // optional placeholder
  ];
  /**
   * Replacements for the patterns index 
   * should be in sink
   *
   * @var array
   * @access private
   */
  private $replacements = [
    '\/', // slash
    '([^\/]++)', // normal placeholder
    '([a-zA-Z]++)', // placeholder accepts only alphabetic chars
    '([0-9a-zA-Z]++)', // placeholder accepts alphabetic and numeric chars
    '([0-9]++)', // placeholder accepts only numeric
    '([0-9a-zA-Z-_]++)', // placeholder accepts alphanumeric and underscore
    '(.++)', // placeholder match rest of url
    '\/?([^\/]*)', // optional placeholder
  ];
  /**
   * <b>__construct</b>
   * 
   * __construct is the constructor method of the Dispacher 
   * class and responsible for setting request data 
   *
   * @return void
   * @access public
   */
  public function __construct(Request $request)
  {
    $this->url = $request->getUrl();
    $this->method = $request->getMethod();
  }
  /**
   * <b>matchRoutes</b>
   * 
   * matchRoutes is the method responsible for 
   * check if the given route is valid according 
   * to the data in the route file
   * 
   * @return boolean
   * @access private
   */
  private function matchRoutes()
  {
    $uri = $this->url;
    
    foreach ($this->routes as $route):
      $matched = true;
      $route['uriPattner'] = preg_replace(
        $this->patterns,
        $this->replacements,
        $route['uri']
      );
      
      $route['uriPattner'] = '#^' . $route['uriPattner'] . '$#';
      if (preg_match($route['uriPattner'], $uri, $matches)) :
        array_shift($matches);
        $params = array_values($matches);
        foreach ($params as $param) :
          if (strpos($param, '/')) :
            $matched = false;
          endif;
        endforeach;
        if ($route['method'] != $this->method) :
          $matched = false;
        endif;
        if ($matched == true) :
          $this->matchRouter = $route;
          $this->matchRouter['uriHttp'] = $uri;
          $this->matchRouter['params'] = $params;
          return true;
        endif;
      endif;
    endforeach;
    return false;
  }
  /**
   * <b>addRoute</b>
   * 
   * addRoute is the methos responsible for 
   * get routes from the routes file and make 
   * them available for use in the system
   * 
   * @param string $method
   * @param string $uri
   * @param string $callback
   * @param string $namespace
   * @return void
   * @access protected 
   */
  protected function addRoute($methods, $uri, $callback, $namespace = null)
  {
    $uri = trim($uri . '/');
    $uri = rtrim($this->prefix . '/' . $uri, '/');
    $uri = $uri ?: '/';

    foreach (explode('|', $methods) as $method):
      $this->routes[] = [
        'uri' => $uri,
        'method' => $method,
        'callback' => $callback,
        'middleware' => $this->middleware,
        'namespace' => $namespace,
      ];
    endforeach;
  }
  /**
   * <b>getRoutes</b>
   * 
   * getRoutes is the method responsible for
   * get routes 
   *
   * @return null|array
   * @access protected
   */
  protected function getRoutes()
  {
    return $this->routes;
  }
  /**
   * <b>dispatch</b>
   * 
   * dispatch is the method responsible for
   * dispatch url and pattern
   * 
   * @param array $route
   * @return void
   * @access private
   */
  private function dispatch($route)
  {
    $callback = $route['callback'];
    if (is_callable($callback)) :
      call_user_func($callback, $route['params']);
    elseif (strpos($callback, '@') !== false) :
      list($controller, $method) = explode('@', $callback);
      $namespace =
        $route['namespace'] != null
          ? $route['namespace'] . '\\'
          : 'App\Controllers\\';
      $controller = $namespace . ucfirst($controller) . 'Controller';

      if (class_exists($controller)) :
        $objectController = new $controller();
        if (method_exists($objectController, $method)) :
          call_user_func_array([$objectController, $method], $route['params']);
        else :
          throw new \BadFunctionCallException("The method {$method} is not exists at {$controller}");
        endif;
      else :
        throw new \ReflectionException("Class {$controller} is not found");
      endif;
    else :
      throw new \InvalidArgumentException("Plase provide valid callback function");
    endif;
  }
  /** 
   * <b>run</b>
   * 
   * run is the methos responsible for
   * verifying and validating URL data
   * 
   * @return void
   * @access public
   */
  public function run()
  {
    if (!is_array($this->routes) || empty($this->routes)) :
      throw new \Exception('NON-Object Route Set'); // treat later
    endif;

    if ($this->matchRoutes()) :
      if (isset($this->matchRouter) && !empty($this->matchRouter)) :
        $this->dispatch($this->matchRouter);
      else :
        throw new \Exception('Match in this url is empty'); 
        $this->sendNotFound();
      endif;
    else :
      throw new \Exception('No match in this url'); // treat later
      $this->sendNotFound();
    endif;
  }
  /**
   * <b>sendNotFound</b>
   * 
   * sendNotFound is the method responsible for 
   * defining not found data if it doesn't find 
   * a valid value in the URL
   * 
   * @return void
   * @access protected
   */
  protected function sendNotFound()
  {
		$this->response->sendStatus(404);
		$this->response->setContent(
      [
        'error' => 'Sorry This Route Not Found !', 
        'status_code' => 404
      ]
    );
	}
}