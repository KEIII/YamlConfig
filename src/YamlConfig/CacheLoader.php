<?php

namespace KEIII\YamlConfig;

use Symfony\Component\Config\ConfigCache;

/**
 * Cache Loader.
 */
class CacheLoader implements SimpleLoaderInterface
{
    /**
     * @var SimpleLoaderInterface
     */
    private $loader;

    /**
     * @var string
     */
    private $cacheDirPath;

    /**
     * @var bool
     */
    private $debug;

    /**
     * Constructor.
     *
     * @param SimpleLoaderInterface $loader
     * @param string                $cacheDirPath
     * @param bool                  $debug
     */
    public function __construct(SimpleLoaderInterface $loader, $cacheDirPath, $debug = true)
    {
        $this->loader = $loader;
        $this->cacheDirPath = (string)$cacheDirPath;
        $this->debug = (bool)$debug;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource)
    {
        $filepath = $this->buildCacheFilepath($resource);
        $configCache = new ConfigCache($filepath, $this->debug);

        if ($configCache->isFresh()) {
            return require $filepath;
        }

        $content = $this->loader->load($resource);
        $fileContent = $this->generateConfigFileContent($content);
        $configCache->write($fileContent);

        return $content;
    }

    /**
     * @param string $resource
     *
     * @return string
     */
    private function buildCacheFilepath($resource)
    {
        return $this->cacheDirPath.DIRECTORY_SEPARATOR.'config_'.md5($resource).'.php';
    }

    /**
     * Generate php file content.
     *
     * @param array $content
     *
     * @return string
     */
    private function generateConfigFileContent(array $content)
    {
        $lines = [];
        $lines[] = '<?php // This file is auto-generated at '.date('c');
        $lines[] = '';
        $lines[] = 'return '.var_export($content, true).';';
        $lines[] = '';

        return implode(PHP_EOL, $lines);
    }
}
