<?php

namespace KEIII\YamlConfig;

class ParametersReplacer
{
    /**
     * @var array
     */
    private $replacements = [];

    /**
     * Constructor.
     *
     * @param array|null $replacements
     */
    public function __construct(array $replacements = null)
    {
        $this->replacements = $replacements ?: [];
    }

    /**
     * Replacements.
     *
     * @param array $content
     *
     * @return array
     */
    public function replace(array $content)
    {
        $replacements = $this->replacements;

        if (array_key_exists('parameters', $content)) {
            if (!is_array($parameters = $content['parameters'])) {
                throw new \InvalidArgumentException(sprintf('Parameters should be array but "%s" given.', gettype($parameters)));
            }

            $replacements = array_replace($parameters, $replacements);
        }

        foreach ($replacements as $paramKey => $paramValue) {
            array_walk_recursive($content, function (&$input) use ($paramKey, $paramValue) {
                $input = str_replace(sprintf('%%%s%%', $paramKey), $paramValue, $input);
            });
        }

        // unescape
        array_walk_recursive($content, function (&$input) {
            $unescaped = preg_replace('/^%%(.*)%%$/', '%${1}%', $input);

            if ((string)$input !== (string)$unescaped) {
                $input = $unescaped;
            }
        });

        return $content;
    }
}
