<?php

namespace KEIII\YamlConfig;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;

/**
 * Loader.
 */
class Loader implements LoaderInterface
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
    public function load($resource, $type = null)
    {
        return $this->parametersReplacer->replace($this->loader->load($resource, $type));
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return $this->supports($resource, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver()
    {
        return $this->getResolver();
    }

    /**
     * {@inheritdoc}
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
        $this->loader->setResolver($resolver);
    }
}
