<?php declare(strict_types=1);
/*------------------------------------------------------------------------
 * ArrayAdapter.php
 * 	
 * 数组驱动
 *
 * Created on alt+t
 *
 * Author: 蚊子 <1423782121@qq.com>
 * 
 * Copyright (c) 2021 All rights reserved.
 * ------------------------------------------------------------------------
 */


namespace Villain\Cache\Adapter;


use Villain\Cache\Exception\InvalidArgumentException;
use Villain\Cache\Concern\AbstractAdapter;

class ArrayAdapter extends AbstractAdapter {
    private array $data = [];

    /**
     * 获取缓存
     * @param $key
     * @param null $default
     * @return mixed|void
     */
    public function get($key, $default = null) {
        // 检查缓存key是否合理
        $this->checkKey($key);

        // 判断是否存在缓存，没有则返回默认值
        if (!isset($this->data[$key])) {
            return $default;
        }

        $row = $this->data[$key];

        // 判断有效期是否在有效期内 否则删除缓存，并返回默认值
        $expireTime = $row[self::TIME_KEY];
        if ($expireTime > 0 && $expireTime < time()) {
            unset($this->data[$key]);
            return $default;
        }

        return $row[self::DATA_KEY];
    }

    /**
     * 设置缓存
     * @param $key
     * @param mixed $value
     * @param null $ttl
     * @return bool
     */
    public function set($key, $value, $ttl = 0): bool {
        // 检查缓存key是否合理
        $this->checkKey($key);

        // 获取缓存时间
        $ttl = $this->formatTTL($ttl);

        // 存储缓存
        $this->data[$key] = [
            self::TIME_KEY => $ttl > 0 ? time() + $ttl : 0,
            self::DATA_KEY => $value,
        ];

        return true;
    }

    /**
     * 删除缓存
     * @param $key
     * @return bool
     */
    public function delete($key): bool {
        // 检查缓存key是否合理
        $this->checkKey($key);

        if (isset($this->data[$key])) {
            unset($this->data[$key]);
            return true;
        }

        return false;
    }

    /**
     * 清除缓存
     * @return bool
     */
    public function clear(): bool {
        $this->data = [];
        return true;
    }

    /**
     * @param array|iterable $keys
     * @param null $default
     * @return iterable
     */
    public function getMultiple($keys, $default = null): iterable {
        // 检测所有key值
        $keys = $this->checkKeys($keys);

        // 获取所有缓存
        $values = [];
        foreach ($keys as $key) {
            $values[$key] = $this->get($key, $default);
        }

        return $values;
    }

    /**
     * 批量设置缓存
     * @param array|iterable $values
     * @param null $ttl
     * @return bool
     */
    public function setMultiple($values, $ttl = null): bool {
        if (!is_array($values)) {
            throw new InvalidArgumentException('The cache keys must be an string array');
        }

        // 设置所有缓存
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }

        return true;
    }

    /**
     * 批量删除缓存
     * @param array|iterable $keys
     * @return bool
     */
    public function deleteMultiple($keys): bool {
        $keys = $this->checkKeys($keys);

        foreach ($keys as $key) {
            $this->delete($key);
        }

        return true;
    }

    /**
     * 判断缓存是否存在
     * @param $key
     * @return bool
     */
    public function has($key): bool {
        $this->checkKey($key);

        return isset($this->data[$key]);
    }

    /**
     * 获取所有缓存
     * @return array
     */
    public function getData(): array {
        return $this->data;
    }

    /**
     * 设置缓存
     * @param array $data
     */
    public function setData(array $data): void {
        $this->data = $data;
    }
}