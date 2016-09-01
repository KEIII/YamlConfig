[![Build Status](https://travis-ci.org/KEIII/YamlConfig.svg?branch=master)](https://travis-ci.org/KEIII/YamlConfig)

Utilize symfony components ([The Config Component](https://symfony.com/doc/current/components/config.html) and [The Yaml Component](https://symfony.com/doc/current/components/yaml.html)) to provide symfony like configuration for any application.

## Installation

```bash
composer require keiii/yaml-config
```

## Config example

```yaml
# /parameters.yml
parameters:
    db_username: 'root'
    db_password: 'secret'
```

```yaml
# /config/config.env.yml
imports:
    - { resource: '../parameters.yml' }

database:
    username: '%db_username%'
    password: '%db_password%'
```

## Usage

```php
<?php

$loader = \KEIII\YamlConfig\Factory::create(
    __DIR__.'/config', // configs path
    ['key' => 'value'], // replacements
    __DIR__.'/var/cache' // cache path or false
);

$config = $loader->load('config.env.yml'); // array
```
