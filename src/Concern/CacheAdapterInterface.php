<?php declare(strict_types=1);


namespace Villain\Cache\Concern;

use Psr\SimpleCache\CacheInterface;

interface CacheAdapterInterface extends CacheInterface {

    /**
     * 缓存是否存在
     * @param $key
     * @return bool
     */
    public function has($key): bool;

    /**
     * 设置缓存
     * @param $key
     * @param mixed $value
     * @param null $ttl
     * @return bool
     */
    public function set($key, $value, $ttl = null): bool;

    /**
     * 删除缓存
     * @param $key
     * @return bool
     */
    public function delete($key): bool;

    /**
     * 同时设置多个缓存
     * @param $values
     * @param null $ttl
     * @return bool
     */
    public function setMultiple($values, $ttl = null): bool;

    /**
     * 删除多个缓存
     * @param $keys
     * @return bool
     */
    public function deleteMultiple($keys): bool;

    /**
     * 清空所有缓存
     * @return bool
     */
    public function clear(): bool;
}