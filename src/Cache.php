<?php
declare(strict_types=1);
/*------------------------------------------------------------------------
 * Cache.php
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

use Psr\SimpleCache\InvalidArgumentException;
use Villain\Cache\Concern\CacheAdapterInterface;

class Cache {
    /**
     * 配置文件
     * @var array
     */
    protected array $config;

    protected CacheAdapterInterface $manager;

    public function __construct(array $config) {
        $this->config = $config;
        $adapter = '';
        if (!isset($this->config['adapter'])) {
            $adapter = 'MultiFileAdapter';
        }
        $application = "\\Villain\\Cache\\Adapter\\{$adapter}";
        $this->manager = new $application($this->config);
    }

    public function set($key, $value, $ttl = 0):bool {
        return $this->manager->set($key, $value, $ttl);
    }

    /**
     * @param $key
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function get($key) {
        return $this->manager->get($key);
    }

    /**
     * @param $key
     * @return bool
     */
    public function delete($key):bool {
        return $this->manager->delete($key);
    }
}