<?php

namespace KEIII\YamlConfig\Tests;

use KEIII\YamlConfig\CacheLoader;
use KEIII\YamlConfig\Factory;
use KEIII\YamlConfig\Loader;

/**
 * Factory Test.
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        self::assertInstanceOf(
            CacheLoader::class,
            Factory::create(__DIR__.'/fixtures', [], sys_get_temp_dir(), false)
        );

        self::assertInstanceOf(
            Loader::class,
            Factory::create(__DIR__.'/fixtures', [], false, true)
        );
    }
}
