Utilize symfony components (The Config Component, The Yaml Component) to provide symfony like configuration for any application.

## Installation

```bash
composer require keiii/yaml-config
```

## Usage

```php
<?php
$loader = \KEIII\YamlConfig\Factory::create(
    __DIR__.'/config', // configs path
    ['key' => 'value'], // replacements
    __DIR__.'/var/cache' // cache path
);
$config = $loader->load('config.env.yml'); // array
```
