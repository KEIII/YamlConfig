<?php

namespace KEIII\YamlConfig;

interface SimpleLoaderInterface
{
    /**
     * Loads a resource.
     *
     * @param mixed $resource The resource
     *
     * @throws \Exception If something went wrong
     */
    public function load($resource);
}
