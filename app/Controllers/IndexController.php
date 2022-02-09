<?php
declare(strict_types=1);

namespace App\Controllers;

use Slick\Core\Controller;

/**
 * <b>IndexController</b>
 * 
 * IndexController is the class initial for redering
 * after start application
 * 
 * @author Antoniel Bordin <antonielbordin@hotmail.com>
 * @copyright © 2019, Stilos Design
 * @version 2.0
 * @since release 2.0
 */
class IndexController extends Controller
{
  public function index()
  {
    echo 'Hello index!';
  }
}