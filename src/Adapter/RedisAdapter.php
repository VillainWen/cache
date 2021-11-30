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


namespace Villain\Cache\Adapter;


use RuntimeException;
use Villain\Cache\Concern\AbstractAdapter;

class RedisAdapter extends AbstractAdapter {
    /**
     * redis 类
     * @var
     */
    private $redis;

    /**
     * The prefix for session key
     *
     * @var string
     */
    protected string $prefix = 'villain_cache:';

    public function init() {
        if (!isset($this->config['redis'])) {
            throw new RuntimeException('must set an datafile for storage cache data');
        }

        $this->setRedis($this->config['redis']);
    }

    /**
     * 设置Redis容器
     * @param $redis
     */
    public function setRedis($redis): void {
        $this->redis = $redis;
    }

    /**
     * 判断缓存Key是否存在
     * @param $key
     * @return bool
     */
    public function has($key): bool  {
        $cacheKey = $this->getCacheKey($key);
        return (bool)$this->redis->exists($cacheKey);
    }

    /**
     * 设置缓存
     * @param $key
     * @param mixed $value
     * @param int $ttl
     * @return bool
     */
    public function set($key, $value, $ttl = 0): bool {
        // 获取缓存Key， 前缀+key
        $cacheKey = $this->getCacheKey($key);

        // 设置获取过期时间
        $ttl   = $this->formatTTL($ttl);

        $value = json_encode($value, 320);

        return (bool)$this->redis->set($cacheKey, $value, $ttl);
    }

    /**
     * 删除缓存
     * @param $key
     * @return bool
     */
    public function delete($key): bool  {
        return $this->redis->del($key) === 1;
    }

    /**
     * 批量设置缓存
     * @param array|iterable $values
     * @param null $ttl
     * @return bool
     */
    public function setMultiple($values, $ttl = 0): bool {
        $ttl = $this->formatTTL($ttl);

        return $this->redis->mset($values, $ttl);
    }

    /**
     * 批量删除缓存
     * @param array|iterable $keys
     * @return bool
     */
    public function deleteMultiple($keys): bool {
        $keys = $this->checkKeys($keys);

        return $this->redis->del(...$keys) === count($keys);
    }

    /**
     * 获取缓存
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function get($key, $default = null) {
        $this->checkKey($key);
        $cacheKey = $this->getCacheKey($key);

        $value = $this->redis->get($cacheKey);
        if ($value === false) {
            return $default;
        }

        return json_decode($value, true);
    }

    /**
     * TODO: wait develop
     * 清空缓存
     * @return bool
     */
    public function clear(): bool {
        return true;
    }

    /**
     * 批量获取缓存
     * @param array|iterable $keys
     * @param null $default
     * @return array
     */
    public function getMultiple($keys, $default = null):array {
        $rows = [];
        $list = $this->redis->mget((array)$keys);

        foreach ($list as $item) {
            $rows[] = json_decode($item, true);
        }

        return $rows;
    }
}