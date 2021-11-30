<?php declare(strict_types=1);

namespace Villain\Cache\Adapter;

use RuntimeException;
use Villain\Cache\Concern\AbstractAdapter;
use function file_exists;
use function filemtime;
use function glob;
use function is_dir;
use function md5;
use function time;
use function unlink;

/**
 * Class MultiFileAdapter
 */
class MultiFileAdapter extends AbstractAdapter {

    /**
     * 缓存文件位置
     * @var string
     */
    private string $savePath = '';

    /**
     * Init $savePath directory
     */
    public function init(): void {
        if (!$this->savePath) {
            $this->savePath = get_temp_dir() . '/villain-caches';
        }

        if (!is_dir($this->savePath)) {
            if (!file_exists($this->savePath) && !mkdir($this->savePath, 0755, true) && !is_dir($this->savePath)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $this->savePath));
            }
        }
    }

    /**
     * 判断Key是否存在
     * @param $key
     * @return bool
     */
    public function has($key): bool
    {
        return file_exists($this->getCacheFile($key));
    }

    /**
     * 设置缓存
     * @param $key
     * @param mixed $value
     * @param null|int $ttl
     * @return bool
     */
    public function set($key, $value, $ttl = 0): bool {
        $file = $this->getCacheFile($key);
        $ttl  = $this->formatTTL($ttl);

        $string = json_encode([
            self::TIME_KEY => $ttl > 0 ? time() + $ttl : 0,
            self::DATA_KEY => $value,
        ], 320);

        return do_write($file, $string);
    }

    /**
     * 批量设置缓存
     * @param array|iterable $values
     * @param null $ttl
     * @return bool
     */
    public function setMultiple($values, $ttl = null): bool {
        $ttl = $this->formatTTL($ttl);

        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }

        return true;
    }

    /**
     * 删除缓存
     * @param $key
     * @return bool
     */
    public function delete($key): bool {
        $file = $this->getCacheFile($key);

        if (file_exists($file)) {
            return unlink($file);
        }

        return false;
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
     * 获取缓存
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function get($key, $default = null) {
        $this->checkKey($key);

        // 读取缓存文件
        $file = $this->getCacheFile($key);
        if (!$string = do_read($file)) {
            return $default;
        }

        // 解析数据
        $item = json_decode($string, true);

        // 检查缓存是否在有效期内
        $expireTime = $item[self::TIME_KEY];
        if ($expireTime > 0 && $expireTime < time()) {
            do_delete($file);
            return $default;
        }

        return $item[self::DATA_KEY];
    }

    /**
     * 批量获取缓存
     * @param array|iterable $keys
     * @param null $default
     * @return array
     */
    public function getMultiple($keys, $default = null):iterable {
        $keys = $this->checkKeys($keys);

        $values = [];
        foreach ($keys as $key) {
            $values[$key] = $this->get($key, $default);
        }

        return $values;
    }

    /**
     * 清空缓存
     * @return bool
     */
    public function clear(): bool {
        // 通过目录和缓存前缀缓存前缀 返回匹配指定模式的文件名或目录
        foreach (glob("{$this->savePath}/{$this->prefix}*") as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }

        return true;
    }

    /**
     * 清除指定时间内的缓存
     * @param int $maxLifetime
     * @return bool
     */
    public function gc(int $maxLifetime): bool {
        $curTime = time();

        foreach (glob("{$this->savePath}/{$this->prefix}*") as $file) {
            if (file_exists($file) && (filemtime($file) + $maxLifetime) < $curTime) {
                unlink($file);
            }
        }

        return true;
    }

    /**
     * 获取缓存存储文件
     * @param string $key
     * @return string
     */
    protected function getCacheFile(string $key): string {
        return $this->savePath . '/' . $this->prefix . md5($key);
    }

    /**
     * 获取缓存保存的路径
     * @return string
     */
    public function getSavePath(): string {
        return $this->savePath;
    }

    /**
     * 设置缓存存储路径
     * @param string $savePath
     */
    public function setSavePath(string $savePath): void {
        $this->savePath = $savePath;
    }
}
