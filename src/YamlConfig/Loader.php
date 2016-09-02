<?php

namespace KEIII\YamlConfig;

use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Loader.
 */
class Loader implements SimpleLoaderInterface
{
    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * @var ParametersReplacer
     */
    private $parametersReplacer;

    /**
     * Constructor.
     *
     * @param LoaderInterface    $loader
     * @param ParametersReplacer $parametersReplacer
     */
    public function __construct(
        LoaderInterface $loader,
        ParametersReplacer $parametersReplacer
    ) {
        $this->loader = $loader;
        $this->parametersReplacer = $parametersReplacer;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource)
    {
        return $this->parametersReplacer->replace($this->loader->load($resource));
    }
}
