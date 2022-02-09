<?php
declare(strict_types=1);
namespace Slick\Core;

/**
 * <b>Request</b> 
 *
 * The request class is responsible for requests
 * made on the system which will be passed on to the router
 *
 * @author Antoniel Bordin <antonielbordin@hotmail.com>
 * @copyright © 2019, Stilos Design
 * @version 2.0
 * @since release 1.0
 */
class Request
{
  /**
   * Variable responsible for storing the
   * base URL of the system
   *
   * @var string $baseUrl
   * @access private
   */
  private $baseUrl;
  /**
   * Variable responsible for storing the url
   * passed in the system
   *
   * @var string $url
   * @access private
   */
  private $url;
  /**
   * Variable responsible for storing the queryString
   * passed in the system
   *
   * @var string $queryString
   * @access private
   */
  private $queryString;
  /**
   * Get COOKIE Super Global
   *
   * @var array $cookie
   * @access private
   * @since Release 2.0
   */
  private $cookie;
  /**
   * Get REQUEST Super Global
   *
   * @var array $request
   * @access private
   * @since Release 2.0
   */
  private $request;
  /**
   * Get FILES Super Global
   *
   * @var array $files
   * @access private
   * @since Release 2.0
   */
  private $files;
  /**
   * Get protocl access
   *
   * @var string $protocol
   * @access private
   * @since Release 2.0
   */
  private $protocol;
  /**
   * Get host name
   *
   * @var string $hostName
   * @access private
   */
  private $hostName;
  /**
   * Get method access
   * 
   * @var string $method
   * @access private
   */
  private $method;
  /**
   * <b>__construct</b>
   * 
   * __construct is the constructor method of the Request 
   * class and responsible for defining the attributes based 
   * on access controlling to php superglobal variables
   *
   * @return void
   * @access public
   */
  public function __construct()
  {
    $this->setMethod();
    $this->setProtocol();
    $this->setHostName();
    $this->setUrl();
    $this->setBaseUrl();
    $this->setRequest();
    // $this->cookie   = $this->setCookie();
    // $this->files    = $this->clean($_FILES);
  }
  /**
   * <b>server</b> 
   * 
   * server is the method responsible for obtaining
   * value for the super global var of the server by passing
   * the parameter to be searched ($key).
   *
   * @param string $key
   * @return null|string
   * @access public
   */
  private function server($key)
  {
    switch ($key) {
      case 'REQUEST_SCHEME':
      case 'REQUEST_METHOD':
      case 'HTTP_HOST':
        return filter_input(INPUT_SERVER, $key, FILTER_DEFAULT);
        break;
      case 'REQUEST_URI':
        return filter_input(INPUT_SERVER, $key, FILTER_SANITIZE_URL);
        break;
      case 'REMOTE_ADDR':
        return filter_input(INPUT_SERVER, $key, FILTER_VALIDATE_IP);
        break;
      default:
        return null;
        break;
    }
  }
  /**
   * <b>setMethod</b> 
   * 
   * setMethod is the method responsible for
   * set the current request method
   *
   * @access private
   * @return void
   */
  private function setMethod()
  {
    $this->method = strtoupper($this->server('REQUEST_METHOD'));
  }
  /**
   * <b>setProtocol</b> 
   * 
   * setProtocol is the method responsible 
   * for setting the access protocol
   * 
   * @access private
   * @return void
   */
  private function setProtocol()
  {
    $this->protocol = $this->server('REQUEST_SCHEME') ?? 'http';
    $this->protocol .= '://';
  }
  /**
   * <b>setHostName</b> 
   * 
   * setHostName is the method responsible
   * for setting the host name
   *
   * @access private
   * @return void
   */
  private function setHostName()
  {
    $this->hostName = $this->server('HTTP_HOST') ?? null;
  }
  /**
   * <b>setBaseUrl</b>
   * 
   * setBaseUrl is the method responsible for
   * setting the base URL of the system
   *
   * @return void
   * @access private
   */
  private function setBaseUrl()
  {
    // TODO: handle query_string case in url
    $this->baseUrl = $this->protocol . $this->hostName;
  }
  /**
   * <b>setUrl</b> 
   * 
   * setUrl is the method responsible for set
   * the corresponding values collected as 
   * url parameters
   *
   * @return void
   * @access private
   */
  private function setUrl()
  {
    $this->queryString = null;
    $this->url = $this->server('REQUEST_URI') ?? '/';
    if (strpos($this->url, '?') !== false) {
      list($url, $queryString) = explode('?', $this->url);
      $this->url = $url;
      $this->queryString = $queryString;
    } 
  }
  /**
   * <b>setRequest</b> 
   * 
   * setRequest is the method responsible for 
   * setting request values that will be used 
   * in the system routing
   *
   * @return void
   * @access private
   */
  private function setRequest()
  {
    $this->request['method'] = $this->getMethod();
    $this->request['protocol'] = $this->getProtocol();
    $this->request['host'] = $this->getHostName();
    $this->request['baseUrl'] = $this->getBaseUrl();
    $this->request['url'] = $this->getUrl();
    $this->request['queryString'] = $this->getQueryString();
  }
  /**
   * <b>getMethod</b> 
   * 
   * getMethod is the method responsible for
   * returning the current request method
   *
   * @return string
   * @access public
   */
  public function getMethod()
  {
    return $this->method;
  }
  /**
   * <b>getProtocol</b> 
   * 
   * getProtocol is the method responsible for
   * returning the current request protocol
   *
   * @return string
   * @access public 
   */
  public function getProtocol()
  {
    return $this->protocol;
  }
  /**
   * <b>getHostName</b> 
   * 
   * getHostName is the method responsible for
   * returning the current request host name
   *
   * @return string
   * @access public  
   */
  public function getHostName()
  {
    return $this->hostName;
  }
  /**
   * <b>getUrl</b> 
   * 
   * getUrl is the method responsible for
   * returning the current request url
   *
   * @return string
   * @access public  
   */
  public function getUrl()
  {
    return $this->url;
  }
  /**
   * <b>getBaseUrl</b> 
   * 
   * getBaseUrl is the method responsible for
   * returning the base URL of the system
   * 
   * @return string
   * @access public
   */
  public function getBaseUrl()
  {
    return $this->baseUrl;
  }
  /**
   * <b>getQueryString</b> 
   * 
   * getQueryString is the method responsible for
   * returning the current request Query String
   * 
   * @return string
   * @access public
   */
  public function getQueryString()
  {
    return $this->queryString;
  }
  /**
   * <b>getRequest</b> 
   * 
   * getRequest is the method responsible for
   * returning the current request
   * 
   * @return array
   * @access public
   */
  public function getRequest()
  {
    return $this->request;
  }
  /**
   * <b>getCookie</b> 
   * 
   * getCookie is the method responsible for
   * returning the current request Cookie
   * 
   * @return array
   * @access public
   */
  public function getCookie(string $key = '')
  {
    if ($key != '')
      return filter_input(INPUT_COOKIE, $key, FILTER_DEFAULT) ?? null;
    
    return $_COOKIE;
  }
  /**
   * <b>get</b>
   * 
   * get is the method responsible for
   * returning the $_GET parameter
   *
   * @param string $key
   * @return string
   * @access public
   */
  public function get(string $key = '')
  {
    if ($key != '') 
      return filter_input(INPUT_GET, $key, FILTER_SANITIZE_URL) ?? null;
    
    return null;
  }
  /**
   * <b>post</b>
   * 
   * post is the method responsible for
   * returning the $_POST parameter
   *
   * @param string $key
   * @return string
   * @access public
   */
  public function post(string $key = '')
  {
    if ($key != '') 
      return filter_input(INPUT_POST, $key, FILTER_DEFAULT) ?? null;
    
    return null;
  }
  /**
   * <b>input</b>
   * 
   * input is the method responsible for
   * returning the input parameter
   *
   * @param string $key
   * @return mixed
   * @access public
   */
  public function input(string $key = '')
  {
    $postData = file_get_contents("php://input");
    $request = json_decode($postData, true);

    if ($key != '') 
      return isset($request[$key]) ? $this->clean($request[$key]) : null;
    
    return $request;
  }
  /**
   * <b>getClientIp</b> 
   * 
   * getClientIp is the method responsible for
   * returning the clients IP address
   *
   * @return string
   * @access public
   */
  public function getClientIp()
  {
    return $this->server('REMOTE_ADDR', true);
  }
  /**
   * <b>clean</b>
   * 
   * clean is the method responsible for
   * Clean Data in array
   *
   * @param mixed $data
   * @return mixed
   * @access private
   */
  private function clean($data)
  {
    if (is_array($data)) {
      foreach ($data as $key => $value) {
        // Delete key
        unset($data[$key]);

        // Set new clean key
        $data[$this->clean($key)] = $this->clean($value);
      }
    } else {
      $data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
    }

    return $data;
  }
}