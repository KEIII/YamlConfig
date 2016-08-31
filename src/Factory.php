<?php

namespace KEIII\YamlConfig;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Yaml\Parser as YamlParser;

class Factory
{
    /**
     * Create a loader instance.
     *
     * @param string      $configPath
     * @param array|null  $replacements
     * @param string|null $cachePath
     *
     * @return LoaderInterface
     */
    public static function create($configPath, array $replacements = null, $cachePath = null)
    {
        $locator = new FileLocator((string)$configPath);
        $yamlParser = new YamlParser();
        $yamlLoader = new YamlLoader($locator, $yamlParser);
        $replacer = new ParametersReplacer($replacements);
        $loader = new Loader($yamlLoader, $replacer);

        return $cachePath ? new CacheLoader($loader, (string)$cachePath) : $loader;
    }
}
