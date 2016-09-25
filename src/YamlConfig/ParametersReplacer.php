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

        $resolveCallback = function ($match) use ($replacements) {
            // skip %%
            if (!array_key_exists(1, $match)) {
                return '%%';
            }

            $key = $match[1];

            if (!array_key_exists($key, $replacements)) {
                throw new \InvalidArgumentException(sprintf('Required parameter "%s" not found.', $key));
            }

            return $replacements[$key];
        };

        array_walk_recursive($content, function (&$input) use ($resolveCallback) {
            if (!is_string($input)) {
                return;
            }

            if (preg_match('/^%([^%\s]+)%$/', $input, $match)) {
                // we do this to deal with non string values (Boolean, integer, ...)
                $input = $resolveCallback($match);
            } else {
                $input = preg_replace_callback('/%%|%([^%\s]+)%/', $resolveCallback, $input);
            }
        });

        return $this->unescape($content);
    }

    /**
     * Unescape parameter placeholders %.
     *
     * @param array|string $value
     *
     * @return array|string
     */
    private function unescape($value)
    {
        if (is_string($value)) {
            return str_replace('%%', '%', $value);
        }

        if (is_array($value)) {
            $result = [];

            foreach ((array)$value as $k => $v) {
                $result[$k] = $this->unescape($v);
            }

            return $result;
        }

        return $value;
    }
}
