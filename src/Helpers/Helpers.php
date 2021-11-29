<?php


if (!function_exists('do_read')) {
    /**
     * 读取文件
     * @param string $file
     * @return string
     */
    function do_read(string $file): string {
        if (!file_exists($file)) {
            return '';
        }
        return (string)file_get_contents($file);
    }
}

if (!function_exists('do_write')) {
    /**
     * 写文件
     * @param string $file
     * @param string $data
     * @return bool
     */
    function do_write(string $file, string $data): bool {
        $cacheDir = dirname($file);
        if (!is_dir($cacheDir)) {
            if (!file_exists($cacheDir) && !mkdir($cacheDir, 0755, true) && !is_dir($cacheDir)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $cacheDir));
            }
        }

        return file_put_contents($file, $data) !== false;
    }
}

if (!function_exists('do_delete')) {
    /**
     * 删除文件
     * @param string $file
     * @return bool
     */
    function do_delete(string $file): bool {
        return unlink($file);
    }
}

if (!function_exists('get_temp_dir')) {
    /**
     * 获取缓存临时目录
     * @return false|mixed|string
     */
    function get_temp_dir() {
        // @codeCoverageIgnoreStart
        if (function_exists('sys_get_temp_dir')) {
            $tmp = sys_get_temp_dir();
        } elseif (!empty($_SERVER['TMP'])) {
            $tmp = $_SERVER['TMP'];
        } elseif (!empty($_SERVER['TEMP'])) {
            $tmp = $_SERVER['TEMP'];
        } elseif (!empty($_SERVER['TMPDIR'])) {
            $tmp = $_SERVER['TMPDIR'];
        } else {
            $tmp = getcwd();
        }
        // @codeCoverageIgnoreEnd

        return $tmp;
    }
}

