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

        if (!isset($this->config['adapter'])) {
            $this->config['adapter'] = 'MultiFileAdapter';
        }

        $adapter = $this->config['adapter'];
        $application = "\\Villain\\Cache\\Adapter\\{$adapter}";

        $this->manager = new $application($this->config);
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key): bool {
        return $this->manager->has($key);
    }

    /**
     * 设置
     * @param $key
     * @param $value
     * @param int $ttl
     * @return bool
     */
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

    /**
     * 获取多个
     * @param $key
     * @param int $default
     * @return iterable
     * @throws InvalidArgumentException
     */
    public function getMultiple($key, $default = 0): iterable {
        return $this->manager->getMultiple($key, $default);
    }

    /**
     * 批量设置
     * @param $values
     * @param null $ttl
     * @return bool
     */
    public function setMultiple($values, $ttl = null): bool {
        return $this->manager->setMultiple($values, $ttl);
    }
}