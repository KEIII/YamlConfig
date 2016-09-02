<?php

namespace KEIII\YamlConfig\tests\YamlConfig\Tests;

use KEIII\YamlConfig\CacheLoader;
use KEIII\YamlConfig\Tests\fixtures\FakeLoader;

/**
 * Cache Loader Test.
 */
class CacheLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $cachePath = sys_get_temp_dir();
        $configResource = 'config.yml';
        $fakeLoader = new FakeLoader();
        $cacheLoader = new CacheLoader($fakeLoader, $cachePath, false);

        $source = $fakeLoader->load($configResource);
        $fresh = $cacheLoader->load($configResource);
        $cached = $cacheLoader->load($configResource);

        $cacheFilepath = $cachePath.DIRECTORY_SEPARATOR.'config_'.md5($configResource).'.php';
        $fromFile = require $cacheFilepath;
        unlink($cacheFilepath);

        self::assertEquals($source, $fresh);
        self::assertEquals($source, $cached);
        self::assertEquals($source, $fromFile);
    }
}
