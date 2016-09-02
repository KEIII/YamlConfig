<?php

namespace KEIII\YamlConfig;

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;

/**
 * Cache Loader.
 */
class CacheLoader implements LoaderInterface
{
    /**
     * @var LoaderInterface
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
     * @param LoaderInterface $loader
     * @param string          $cacheDirPath
     * @param bool            $debug
     */
    public function __construct(LoaderInterface $loader, $cacheDirPath, $debug = true)
    {
        $this->loader = $loader;
        $this->cacheDirPath = (string)$cacheDirPath;
        $this->debug = (bool)$debug;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
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

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return $this->loader->supports($resource, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver()
    {
        return $this->loader->getResolver();
    }

    /**
     * {@inheritdoc}
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
        $this->loader->setResolver($resolver);
    }
}
