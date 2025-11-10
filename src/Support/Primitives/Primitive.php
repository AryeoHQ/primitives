<?php

declare(strict_types=1);

namespace Support\Primitives;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Support\Traits\Macroable;

abstract class Primitive implements Castable, Contracts\Primitive
{
    use Macroable;

    final public static function make(mixed $value): static
    {
        return match ($value instanceof static) {
            true => $value,
            false => app(static::class, ['value' => $value]),
        };
    }
}
