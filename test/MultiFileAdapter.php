<?php
namespace Villain\Cache;
require_once '../vendor/autoload.php';

use Villain\Cache\Adapter\MultiFileAdapter;

$a = new MultiFileAdapter();
$a->set('aaaab', 'bbbb');
$b = $a->get('aaaa');
var_dump($b);

$c = $a->getSavePath();
var_dump($c);
