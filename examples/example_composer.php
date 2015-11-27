<?php namespace Wellic\Example;

//before use start 'composer update'
require_once 'vendor/autoload.php';

use Wellic\ns1;
use Wellic\ns2;

$a = new ns1\t();
$a->f();

$a = new ns2\t();
$a->f();
