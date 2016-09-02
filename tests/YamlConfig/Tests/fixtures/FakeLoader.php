<?php

namespace KEIII\YamlConfig\Tests\fixtures;

use KEIII\YamlConfig\SimpleLoaderInterface;

/**
 * Return a data array.
 */
class FakeLoader implements SimpleLoaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function load($resource)
    {
        return ['root' => ['key' => 'value']];
    }
}
