<?php

namespace Support\Primitives\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Support\Primitives\Text;

/** @implements CastsAttributes<Text, Text|mixed> */
class AsText implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return Text::make($value);
    }

    public function set($model, $key, $value, $attributes)
    {
        return Text::make($value)->toString();
    }
}
