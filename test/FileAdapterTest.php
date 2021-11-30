<?php
namespace Villain\Cache;

use Villain\Cache\Adapter\FileAdapter;

require_once '../vendor/autoload.php';

$config = [
    'dataFile' => __DIR__ . '/catch.txt'
];

$cache = new Cache($config);
$cache->set('key', 'value');

var_dump($cache->get('key'));