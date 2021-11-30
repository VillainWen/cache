# cache

PHP Cache Component

## Install

- composer command

```bash
composer require villain/cache
```

## Example

### MultiFile

```bash
$config = [
    'adapter' => 'MultiFileAdapter'
];

$cache = new Cache($config);
$cache->set('key', 'value');
```

### File

```bash
$config = [
'dataFile' => __DIR__ . '/catch.txt'
];

$cache = new Cache($config);
$cache->set('key', 'value');

var_dump($cache->get('key'));
```

### Array

```bash
$config = [];

$cache = new Cache($config);
$cache->set('key', 'value');

var_dump($cache->get('key'));
```