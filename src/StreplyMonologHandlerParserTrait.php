<?php

namespace Streply\Monolog;

trait StreplyMonologHandlerParserTrait
{
    private function parseContext(array $context): array
    {
        $params = [];

        foreach($context as $name => $value) {
            if(
                is_string($name) &&
                (is_string($value) || is_int($value) || is_float($value) || is_array($value) || is_null($value) || is_bool($value))
            ) {
                $params[$name] = $value;
            }
        }

        return $params;
    }
}