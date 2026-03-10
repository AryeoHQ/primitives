<?php

declare(strict_types=1);

namespace Support\Primitives\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Support\Primitives\Sort;
use Support\Primitives\Text;

/** @implements CastsAttributes<Sort, Sort|mixed> */
class AsSort implements CastsAttributes
{
    public function get($model, $key, $value, $attributes): null|Sort
    {
        if ($value === null) {
            return null;
        }

        return Sort::make(Text::make($value)->toString());
    }

    public function set($model, $key, $value, $attributes): null|string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Sort) {
            return $value->toText()->toString();
        }

        return Text::make($value)->toString();
    }
}
