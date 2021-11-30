<?php
namespace Villain\Cache;

require_once '../vendor/autoload.php';

$config = [
    'adapter' => 'MultiFileAdapter'
];

$cache = new Cache($config);
$cache->set('key', 'value');

var_dump($cache->get('key'));

