<?php
namespace Villain\Cache;

use Villain\Cache\Adapter\FileAdapter;

require_once '../vendor/autoload.php';

$a = new FileAdapter();

$a->setDataFile(__DIR__ . '/text.txt');
$a->set('a', 'b');

$c = $a->get('a');
var_dump($c);