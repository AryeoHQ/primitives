<?php

namespace Support\Primitives\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Support\Primitives\Boolean;

/** @implements CastsAttributes<\Support\Primitives\Boolean, \Support\Primitives\Boolean|mixed> */
class AsBoolean implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return Boolean::make($value);
    }

    public function set($model, $key, $value, $attributes)
    {
        return Boolean::make($value)->value;
    }
}
