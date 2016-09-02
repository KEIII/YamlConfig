<?php

namespace KEIII\YamlConfig\Tests\fixtures;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;

/**
 * Return a data array.
 */
class FakeLoader implements LoaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        return ['root' => ['key' => 'value']];
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        throw new \LogicException('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver()
    {
        throw new \LogicException('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
        throw new \LogicException('Not implemented.');
    }
}
