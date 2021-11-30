<?php declare(strict_types=1);
/*------------------------------------------------------------------------
 * FileAdapter.php
 * 	
 * 文件缓存
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

class FileAdapter extends ArrayAdapter {

    /**
     * 缓存文件位置
     * @var string
     */
    protected string $dataFile = '';

    /**
     * 初始化
     */
    public function init() {
        if (!isset($this->config['dataFile'])) {
            throw new RuntimeException('must set an datafile for storage cache data');
        }

        $this->setDataFile($this->config['dataFile']);

        if (!$this->dataFile) {
            throw new RuntimeException('must set an datafile for storage cache data');
        }

        $this->loadData();
    }

    /**
     * 导入数到数组缓存
     */
    public function loadData(): void {
        $file = $this->getDataFile();

        if ($string = do_read($file)) {
            $this->setData(json_decode($string, true));
        }
    }

    /**
     * 将数据写入到文件里
     * @return bool
     */
    public function saveData (): bool {
        // 获取文件地址
        $file = $this->getDataFile();

        // 默认数据
        $string = '';

        if ($data = $this->getData()) {
            $string = json_encode($data, 320);
        }

        return do_write($file, $string);
    }

    /**
     * 将数组写入到缓存，然后把数据写入到文件里
     * @param $key
     * @param mixed $value
     * @param int $ttl
     * @return bool
     */
    public function set($key, $value, $ttl = 0): bool {
        if (parent::set($key, $value, $ttl)) {
            return $this->saveData();
        }

        return false;
    }

    /**
     * 删除缓存
     * @param $key
     * @return bool
     */
    public function delete($key): bool
    {
        if (parent::delete($key)) {
            $this->saveData();
        }

        return false;
    }

    /**
     * 批量添加
     * @param array|iterable $values
     * @param null $ttl
     * @return bool
     */
    public function setMultiple($values, $ttl = null): bool
    {
        if (parent::setMultiple($values, $ttl)) {
            $this->saveData();
        }

        return false;
    }

    /**
     * 批量删除
     * @param array|iterable $keys
     * @return bool
     */
    public function deleteMultiple($keys): bool {
        if (parent::deleteMultiple($keys)) {
            $this->saveData();
        }

        return false;
    }

    /**
     * 清空数据
     * @return bool
     */
    public function clear(): bool {
        parent::clear();

        return $this->saveData();
    }

    /**
     * 关闭
     * @return bool
     */
    public function close(): bool  {
        return $this->saveData();
    }

    /**
     * @return string
     */
    public function getDataFile(): string {
        return $this->dataFile;
    }

    /**
     * @param string $dataFile
     */
    public function setDataFile(string $dataFile): void
    {
        $this->dataFile = $dataFile;
    }
}