<?php declare(strict_types=1);
/*------------------------------------------------------------------------
 * AbstractAdapter.php
 * 	
 * 缓存抽象适配器
 *
 * 缓存存储对象为key-value形式
 *
 * value 格式为数组字符串  t-有效期 d-数据 例如：json_encode(['t' => 'xx', 'd' => 'xx'], 320);
 *
 * Created on alt+t
 *
 * Author: 蚊子 <1423782121@qq.com>
 * 
 * Copyright (c) 2021 All rights reserved.
 * ------------------------------------------------------------------------
 */


namespace Villain\Cache\Concern;

use DateInterval;
use DateTime;
use Traversable;
use Villain\Cache\Exception\InvalidArgumentException;


abstract class AbstractAdapter implements CacheAdapterInterface {

    public function __construct() {
    }

    /**
     * 缓存有效期的key值
     */
    public const TIME_KEY = 't';

    /**
     * 缓存数据的key值
     */
    public const DATA_KEY = 'd';

    /**
     * 缓存key前缀
     * @var string
     */
    protected string $prefix = 'cache_';

    /**
     * 检查key是否为字符串
     * @param $key
     */
    protected function checkKey($key): void {
        if (!is_string($key)) {
            throw new InvalidArgumentException('The cache key must be an string');
        }
    }

    /**
     * 检查keys是否符合要求
     * @param array|Traversable $keys
     * @return array
     */
    protected function checkKeys($keys): array {
        // 如果是可以遍历的，是则把参数转变为数组
        if ($keys instanceof Traversable) {
            $keys = iterator_to_array($keys, false);
        } elseif (!is_array($keys)) {
            // 如果不是可遍历或者数组则提示异常
            throw new InvalidArgumentException('The cache keys must be an string array');
        }

        return $keys;
    }

    /**
     * 获取时间
     * @param DateInterval $ttl
     * @return int
     */
    protected function getTTL (DateInterval $ttl): int {
        $secs = DateTime::createFromFormat('U', '0')->add($ttl)->format('U');
        return intval(abs($secs));
    }

    /**
     * 格式化时间
     * @param int|DateInterval|null $ttl
     * @return int
     */
    protected function formatTTL ($ttl):int {
        if (is_int($ttl)) {
            return $ttl < 1 ? 0 : $ttl;
        }

        if ($ttl instanceof DateInterval) {
            return $this->getTTL($ttl);
        }

        $msgTpl = 'Expiration date must be an integer, a DateInterval, "%s" given';
        throw new InvalidArgumentException(sprintf($msgTpl, is_object($ttl) ? get_class($ttl) : gettype($ttl)));
    }

    /**
     * 获取缓存的Key
     * @param string $key
     * @return string
     */
    protected function getCacheKey(string $key): string {
        return $this->prefix . $key;
    }

    /**
     * 获取缓存前缀
     * @return string
     */
    public function getPrefix(): string {
        return $this->prefix;
    }

    /**
     * 设置缓存前缀
     * @param string $prefix
     */
    public function setPrefix(string $prefix): void {
        $this->prefix = $prefix;
    }
}