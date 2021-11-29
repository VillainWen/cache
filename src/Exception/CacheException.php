<?php declare(strict_types=1);

namespace Villain\Cache\Exception;

use RuntimeException;

class CacheException extends RuntimeException implements \Psr\SimpleCache\CacheException
{

}
