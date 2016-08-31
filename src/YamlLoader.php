<?php

namespace KEIII\YamlConfig;

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Parser as YamlParser;

/**
 * Loader loads YAML files.
 */
class YamlLoader extends FileLoader
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
        $content = $this->parser->parse($this->fileGetContent($filepath));
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
        $imports = $content['imports'];
        unset($content['imports']);
        $parts = [$content];

        foreach ($imports as $import) {
            if (!is_array($import)) {
                throw new \InvalidArgumentException(sprintf('The values in the "imports" key should be arrays in %s. Check your YAML syntax.', $filepath));
            }

            /** @noinspection DisconnectedForeachInstructionInspection */
            $this->setCurrentDir($defaultDirectory);
            $ignoreErrors = array_key_exists('ignore_errors', $import) ? (bool)$import['ignore_errors'] : false;
            $parts[] = $this->import($import['resource'], null, $ignoreErrors, $filepath);
        }

        return call_user_func_array('array_replace_recursive', array_reverse($parts));
    }

    /**
     * @param string $filepath
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    private function validateFile($filepath)
    {
        if (!is_file($filepath)) {
            throw new \RuntimeException(sprintf('File "%s" not found.', $filepath));
        }

        if (!is_readable($filepath)) {
            throw new \RuntimeException(sprintf('File "%s" is not readable.', $filepath));
        }

        return (string)$filepath;
    }

    /**
     * @param string $filepath
     *
     * @return string
     */
    private function fileGetContent($filepath)
    {
        return file_get_contents($this->validateFile($filepath));
    }
}
