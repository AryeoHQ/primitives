<?php

declare(strict_types=1);

namespace Support\Primitives\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Support\Primitives\Boolean;

/** @implements CastsAttributes<bool, bool> */
class AsBoolean implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return Boolean::make($value);
    }

    public function set($model, $key, $value, $attributes)
    {
        return Boolean::make($value);
    }
}
