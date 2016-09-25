<?php

namespace KEIII\YamlConfig\Tests;

use KEIII\YamlConfig\ParametersReplacer;

/**
 * Parameters Replacer Test.
 */
class ParametersReplacerTest extends \PHPUnit_Framework_TestCase
{
    public function testReplace()
    {
        $replacements = [
            'version' => 123,
            'dynamicKey' => 'dynamicValue2',
        ];
        $replacer = new ParametersReplacer($replacements);
        $src = [
            'version' => '%version%',
            'key' => '%oldvalue%',
            'key2' => '%dynamicKey%',
            'key3' => '%dynamicKey%withappendix',
            'href' => '%host%:%port%',
            'href2' => '%%host%%:%%port%%',
            'href3' => '%%%host%%%:%%%port%%%',
            'parameters' => [
                'oldvalue' => 'newvalue',
                'dynamicKey' => 'dynamicValue1',
                'host' => 'localhost',
                'port' => 8080,
            ],
        ];
        $expected = [
            'version' => 123,
            'key' => 'newvalue',
            'key2' => 'dynamicValue2',
            'key3' => 'dynamicValue2withappendix',
            'href' => 'localhost:8080',
            'href2' => '%host%:%port%',
            'href3' => '%localhost%:%8080%',
            'parameters' => [
                'oldvalue' => 'newvalue',
                'dynamicKey' => 'dynamicValue1',
                'host' => 'localhost',
                'port' => 8080,
            ],
        ];

        $actual = $replacer->replace($src);

        self::assertSame($expected, $actual);
    }
}
