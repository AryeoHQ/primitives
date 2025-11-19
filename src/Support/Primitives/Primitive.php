<?php

declare(strict_types=1);

namespace Support\Primitives;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Support\Traits\Macroable;
use ReflectionClass;

abstract class Primitive implements Castable, Contracts\Primitive
{
    use Macroable;

    final public static function make(mixed ...$value): static
    {
        return match (count($value) === 1 && $value[0] instanceof static) {
            true => $value[0],
            false => static::makeDynamically(static::class, $value),
        };
    }

    /**
     * Laravel’s container app() function accepts an array as the second argument, but it only maps
     * by constructor parameter name. This method uses reflection to take indexed array and convert
     * it into an associative array with the correct constructor parameter names.
     */
    final protected static function makeDynamically(string $class, array $params): static
    {
        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        if (! $constructor) {
            return app($class);
        }

        $args = [];
        $index = 0;

        foreach ($constructor->getParameters() as $param) {
            if (array_key_exists($index, $params)) {
                $args[$param->getName()] = $params[$index];
            }
            $index++;
        }

        return app($class, $args);
    }
}
