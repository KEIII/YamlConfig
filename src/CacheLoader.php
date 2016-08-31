<?php

namespace KEIII\YamlConfig;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Filesystem\Filesystem;

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
     * Constructor.
     *
     * @param LoaderInterface $loader
     * @param $cacheDirPath
     */
    public function __construct(LoaderInterface $loader, $cacheDirPath)
    {
        $this->loader = $loader;
        $this->cacheDirPath = $cacheDirPath;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        $cacheFilepath = !$this->cacheDirPath ? false : $this->buildCacheFilepath($resource);
        $hasCacheFile = !$cacheFilepath ? false : is_readable($cacheFilepath);

        if ($hasCacheFile) {
            return require $cacheFilepath;
        }

        $content = $this->loader->load($resource);

        if ($cacheFilepath) {
            $fileContent = $this->generateConfigFileContent($content);
            $filesystem = new Filesystem();
            $filesystem->dumpFile($cacheFilepath, $fileContent);
        }

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
