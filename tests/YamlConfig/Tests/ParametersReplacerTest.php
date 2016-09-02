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
            'parameters' => [
                'oldvalue' => 'newvalue',
                'dynamicKey' => 'dynamicValue1',
            ],
        ];
        $expected = [
            'version' => 123,
            'key' => 'newvalue',
            'key2' => 'dynamicValue2',
            'parameters' => [
                'oldvalue' => 'newvalue',
                'dynamicKey' => 'dynamicValue1',
            ],
        ];

        self::assertEquals($expected, $replacer->replace($src));
    }
}
