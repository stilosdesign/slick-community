<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROUTE', realpath(dirname(__FILE__)) . DS);

require __DIR__ . '/vendor/autoload.php';

use Slick\Settings\Defines;
use Slick\Core\App;

Defines::init();

App::start();