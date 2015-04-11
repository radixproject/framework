<?php

use Symfony\Component\HttpFoundation\Request;
use Radix\Framework\Radix;

require_once __DIR__.'/../vendor/autoload.php';

date_default_timezone_set('UTC');

$radix = new Radix();
$radix->registerApp(new \Radix\App\Example\ExampleApp());
$radix->run();

//$request = Request::createFromGlobals();
//$app->run($request);
