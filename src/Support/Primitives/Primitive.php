<?php

namespace Support\Primitives;

use Illuminate\Support\Traits\Macroable;

abstract class Primitive implements Contracts\Primative
{
    use Macroable;

    final public static function make(mixed $value): static
    {
        /** @var static */
        return app(static::class, ['value' => $value]);
    }
}
