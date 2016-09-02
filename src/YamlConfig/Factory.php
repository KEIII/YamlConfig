<?php

namespace KEIII\YamlConfig;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Parser as YamlParser;

class Factory
{
    /**
     * Create a loader instance.
     *
     * @param string      $configPath
     * @param array|null  $replacements
     * @param string|null $cachePath
     * @param bool        $debug
     *
     * @return SimpleLoaderInterface
     */
    public static function create($configPath, array $replacements = null, $cachePath = null, $debug = true)
    {
        $locator = new FileLocator((string)$configPath);
        $yamlParser = new YamlParser();
        $yamlLoader = new YamlLoader($locator, $yamlParser);
        $replacer = new ParametersReplacer($replacements);
        $loader = new Loader($yamlLoader, $replacer);

        return $cachePath ? new CacheLoader($loader, $cachePath, $debug) : $loader;
    }
}
