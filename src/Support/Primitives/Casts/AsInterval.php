<?php

namespace Support\Primitives\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Support\Primitives\Interval;
use Support\Primitives\Text;

/** @implements CastsAttributes<Interval, Interval|mixed> */
class AsInterval implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return Text::make($value)->toInterval();
    }

    public function set($model, $key, $value, $attributes)
    {
        if ($value instanceof Interval) {
            return $value->toText()->toString();
        }

        return Text::make($value)->toString();
    }
}
