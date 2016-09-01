<?php

namespace KEIII\YamlConfigTests;

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
        ];
        $replacer = new ParametersReplacer($replacements);
        $src = [
            'version' => '%version%',
            'key' => '%oldvalue%',
            'parameters' => [
                'oldvalue' => 'newvalue',
            ],
        ];
        $expected = [
            'version' => 123,
            'key' => 'newvalue',
            'parameters' => [
                'oldvalue' => 'newvalue',
            ],
        ];

        self::assertEquals($expected, $replacer->replace($src));
    }
}
