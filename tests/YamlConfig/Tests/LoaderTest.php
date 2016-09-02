<?php

namespace KEIII\YamlConfig\Tests;

use KEIII\YamlConfig\Loader;
use KEIII\YamlConfig\ParametersReplacer;
use KEIII\YamlConfig\YamlLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Parser as YamlParser;

/**
 * Loader Test.
 */
class LoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $locator = new FileLocator(__DIR__.DIRECTORY_SEPARATOR.'fixtures');
        $yamlParser = new YamlParser();
        $yamlLoader = new YamlLoader($locator, $yamlParser);
        $replacer = new ParametersReplacer(['version' => 123]);
        $loader = new Loader($yamlLoader, $replacer);

        $expected = [
            'root' => [
                'key' => 'value',
                'replaced' => 'somevalue',
                'version' => 123,
                'escaped' => '%ver%ion%',
            ],
            'parameters' => [
                'somekey' => 'somevalue',
            ],
            'imported' => [
                'key' => 'value',
            ],
        ];

        self::assertEquals($expected, $loader->load('config.yml'));
    }
}
