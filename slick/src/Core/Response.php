<?php
namespace Slick\Core;

/**
 * <b>Response</b>
 * 
 * Response is a class responsible for managing responses 
 * in the HTTP standard
 * 
 * @author Antoniel Bordin <antonielbordin@hotmail.com>
 * @copyright © 2019, Stilos Design
 * @version 2.0
 * @since release 2.0
 */
class Response
{
  /**
   * Stores response header values
   * 
   * @var array $headers
   * @access protected
   */
  protected $headers = [];  
  /**
   * Store the texts as the HTTP 
   * response codes
   * 
   * @var array $statusTexts
   * @access protected
   */
  protected $statusTexts = [
      // INFORMATIONAL CODES
      100 => 'Continue',
      101 => 'Switching Protocols',
      102 => 'Processing',
      // SUCCESS CODES
      200 => 'OK',
      201 => 'Created',
      202 => 'Accepted',
      203 => 'Non-Authoritative Information',
      204 => 'No Content',
      205 => 'Reset Content',
      206 => 'Partial Content',
      207 => 'Multi-status',
      208 => 'Already Reported',
      // REDIRECTION CODES
      300 => 'Multiple Choices',
      301 => 'Moved Permanently',
      302 => 'Found',
      303 => 'See Other',
      304 => 'Not Modified',
      305 => 'Use Proxy',
      306 => 'Switch Proxy', // Deprecated
      307 => 'Temporary Redirect',
      // CLIENT ERROR
      400 => 'Bad Request',
      401 => 'Unauthorized',
      402 => 'Payment Required',
      403 => 'Forbidden',
      404 => 'Not Found',
      405 => 'Method Not Allowed',
      406 => 'Not Acceptable',
      407 => 'Proxy Authentication Required',
      408 => 'Request Time-out',
      409 => 'Conflict',
      410 => 'Gone',
      411 => 'Length Required',
      412 => 'Precondition Failed',
      413 => 'Request Entity Too Large',
      414 => 'Request-URI Too Long',
      415 => 'Unsupported Media Type',
      416 => 'Requested range not satisfiable',
      417 => 'Expectation Failed',
      418 => 'I\'m a teapot',
      422 => 'Unprocessable Entity',
      423 => 'Locked',
      424 => 'Failed Dependency',
      425 => 'Unordered Collection',
      426 => 'Upgrade Required',
      428 => 'Precondition Required',
      429 => 'Too Many Requests',
      431 => 'Request Header Fields Too Large',
      // SERVER ERROR
      500 => 'Internal Server Error',
      501 => 'Not Implemented',
      502 => 'Bad Gateway',
      503 => 'Service Unavailable',
      504 => 'Gateway Time-out',
      505 => 'HTTP Version not supported',
      506 => 'Variant Also Negotiates',
      507 => 'Insufficient Storage',
      508 => 'Loop Detected',
      511 => 'Network Authentication Required',
  ];
  /** 
   * Stores the http protocol version
   *
   * @var string $version
   * @access protected
   */
  protected $version;
  /**
   * Stores content the http response
   * 
   * @var string $content
   * @access protected 
   */
  protected $content;
  /**
   * <b>__construct</b>
   * 
   * __construct is the response class 
   * constructor method
   * 
   * @return void
   * @access public 
   */
  public function __construct () {
    $this->setVersion('1.1');
  }
  /**
   * <b>setVersion</b>
   * 
   * setVersion is the method responsible
   * for set the Http protocol version
   *
   * @param string $version
   * @return void
   * @access public
   */
  public function setVersion(string $version) {
    $this->version = $version;
  }
  /**
   * <b>getVersion</b>
   * 
   * getVersion is the method responsible 
   * for getting the http protocol version
   *
   * @return string
   * @access public
   */
  public function getVersion() {
    return $this->version;
  }
  /**
   * <b>getStatusCodeText</b>
   * 
   * getStatusCodeText is the method responsible 
   * for returning the text according to the 
   * status code informed
   *
   * @param int $code
   * @return string
   * @access public
   */
  public function getStatusCodeText(int $code) {
    return (string) isset($this->statusTexts[$code]) ? $this->statusTexts[$code] : 'unknown status';
  }
  /**
   * <b>setHeader</b>
   * 
   * setHeader is the method responsible 
   * for set response headers
   *
   * @param string $header
   * @return void
   * @access public
   */
  public function setHeader(String $header) {
    $this->headers[] = $header;
  }
  /**
   * <b>getHeader</b>
   * 
   * getHeader is the method responsible 
   * for returning response headers
   *
   * @return array
   * @access public
   */
  public function getHeader() {
    return $this->headers;
  }
  /**
   * <b>setContent</b>
   * 
   * setContent is the method responsible 
   * for setting response content
   *
   * @param $content
   * @return void
   * @access public
   */
  public function setContent($content) {
    $this->content = json_encode($content);
  }
  /**
   * <b>getContent</b>
   * 
   * getContent is the method responsible 
   * for returning response content
   *
   * @return mixed
   * @access public
   */
  public function getContent() {
    return $this->content;
  }
  /**
   * <b>redirect</b>
   * 
   * redirect is the method responsible for
   * redirect page
   * 
   * @param $url
   * @return void
   * @access public
   */
  public function redirect($url) {
    if (empty($url)) {
      trigger_error('Cannot redirect to an empty URL.');
      exit;
    }

    header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&','', ''), $url), true, 302);
    exit;
  }
  /**
   * <b>isInvalid</b>
   * 
   * isInvalid is the method for check if 
   * status code is invalid
   *
   * @return bool
   * @access public
   */
  public function isInvalid(int $statusCode) {
    return $statusCode < 100 || $statusCode >= 600;
  }
  /**
   * <b>sendStatus</b>
   * 
   * sendStatus is the method responsible 
   * for adding status code for rendering 
   * the response
   * 
   * @param $code
   * @return void
   * @access public
   */
  public function sendStatus($code) {
    if (!$this->isInvalid($code)) {
      $this->setHeader(sprintf('HTTP/1.1 ' . $code . ' %s' , $this->getStatusCodeText($code)));
    }
  }
  /**
   * <b>render</b>
   * 
   * render is the method responsible 
   * for rendering the response
   * 
   * @return $output
   * @access public
   */
  public function render() {
    if ($this->content) {
      $output = $this->content;
      if (!headers_sent()) {
        foreach ($this->headers as $header) {
          header($header, true);
        }
      }      
      echo '|' . $output;
    }
  }
}
