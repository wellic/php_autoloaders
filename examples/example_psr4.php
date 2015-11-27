<?php namespace Wellic\Example;

require_once '../Psr4ClassLoader.php';
$loader = new \ClassLoader\Psr4ClassLoader();
$loader->register();
$loader->addPrefix('Wellic', array(
    'demosrc/classes',
    'demosrc/classes2_or_interfaces'
));

use Wellic\ns1;
use Wellic\ns2;

$a = new ns1\t();
$a->f();

$a = new ns2\t();
$a->f();
