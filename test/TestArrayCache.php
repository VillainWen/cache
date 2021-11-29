<?php declare(strict_types=1);

/*------------------------------------------------------------------------
 * File.php
 * 	
 * Description
 *
 * Created on alt+t
 *
 * Author: 蚊子 <1423782121@qq.com>
 * 
 * Copyright (c) 2021 All rights reserved.
 * ------------------------------------------------------------------------
 */
namespace Villain\Cache;

require_once '../vendor/autoload.php';

use Villain\Cache\Adapter\ArrayAdapter;

$a = new ArrayAdapter();
$a->set('label', 'value');
$b = $a->get('label');
var_dump($b);
$c = $a->getData();

var_dump($c);