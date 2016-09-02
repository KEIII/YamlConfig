<?php

namespace KEIII\YamlConfig;

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Parser as YamlParser;

/**
 * Loader loads YAML files.
 */
class YamlLoader extends FileLoader implements SimpleLoaderInterface
{
    /**
     * @var YamlParser
     */
    private $parser;

    /**
     * Constructor.
     *
     * @param FileLocatorInterface $locator
     * @param YamlParser           $parser
     */
    public function __construct(
        FileLocatorInterface $locator,
        YamlParser $parser
    ) {
        parent::__construct($locator);

        $this->parser = $parser;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        $filepath = $this->getLocator()->locate($resource);
        $content = $this->parser->parse(file_get_contents($filepath));
        $content = $this->parseImports($content, $resource);

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION);
    }

    /**
     * Parses all imports.
     *
     * @param array  $content
     * @param string $filepath
     *
     * @return array
     */
    private function parseImports(array $content, $filepath)
    {
        if (!array_key_exists('imports', $content)) {
            return $content;
        }

        if (!is_array($content['imports'])) {
            throw new \InvalidArgumentException(sprintf('The "imports" key should contain an array in %s. Check your YAML syntax.', $filepath));
        }

        $defaultDirectory = dirname($filepath);
        $imports = (array)$content['imports'];
        unset($content['imports']);
        $parts = [$content];

        foreach ($imports as $import) {
            if (!is_array($import)) {
                throw new \InvalidArgumentException(sprintf('The values in the "imports" key should be arrays in %s. Check your YAML syntax.', $filepath));
            }

            if (!array_key_exists('resource', $import)) {
                throw new \InvalidArgumentException('Resource not found.');
            }

            $this->setCurrentDir($defaultDirectory);
            $ignoreErrors = array_key_exists('ignore_errors', $import) ? (bool)$import['ignore_errors'] : false;
            $parts[] = $this->import($import['resource'], null, $ignoreErrors, $filepath);
        }

        return call_user_func_array('array_replace_recursive', array_reverse($parts));
    }
}
