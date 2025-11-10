<?php

declare(strict_types=1);

namespace Support\Primitives;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Support\Traits\Macroable;
use Support\Primitives\Casts\AsBoolean;

final class Boolean implements Castable
{
    use Macroable;

    final public static function make(int|float|string|bool|Text|Number $value): bool
    {
        return match (true) {
            is_bool($value) => $value,
            $value instanceof Number => (bool) $value->value,
            default => filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false,
        };
    }

    /**
     * @return class-string<\Illuminate\Contracts\Database\Eloquent\CastsAttributes<bool, bool>>
     */
    public static function castUsing(array $arguments): string
    {
        return AsBoolean::class;
    }
}
