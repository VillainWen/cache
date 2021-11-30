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

$config = [];

$cache = new Cache($config);
$cache->set('key1', 'value');

var_dump($cache->get('key'));