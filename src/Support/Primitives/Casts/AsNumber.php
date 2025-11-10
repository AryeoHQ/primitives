<?php

declare(strict_types=1);

namespace Support\Primitives\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Support\Primitives\Number;

/** @implements CastsAttributes<Number, Number|mixed> */
class AsNumber implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return Number::make($value);
    }

    public function set($model, $key, $value, $attributes)
    {
        return Number::make($value)->value;
    }
}
