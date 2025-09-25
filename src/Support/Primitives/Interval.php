<?php

namespace Support\Primitives;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use Support\Primitives\Casts\AsInterval;

final class Interval implements Castable, Contracts\Primative
{
    use Macroable;

    public readonly string|Carbon|Number|Text|float|int|null $min;

    public readonly string|Carbon|Number|Text|float|int|null $max;

    public function __construct(string|Carbon|CarbonPeriod|Number|Text|float|int|null $min = null, string|Carbon|CarbonPeriod|Number|Text|float|int|null $max = null)
    {
        if ($min instanceof CarbonPeriod) {
            $this->min = $this->parseValue($min->getStartDate());
            $this->max = $this->parseValue($min->getEndDate());

            return;
        }

        $min = $this->parseValue($min);
        $max = $this->parseValue($max);

        if ($min !== null && $max !== null && ! ($min instanceof $max)) {
            throw new InvalidArgumentException('Min and max must resolve to the same instance type.');
        }

        $this->min = $min;
        $this->max = $max;
    }

    /**
     * @return class-string<CastsAttributes<Interval, Interval|mixed>>
     */
    public static function castUsing(array $arguments): string
    {
        return AsInterval::class;
    }

    public static function make(string|Carbon|CarbonPeriod|Number|Text|float|int|null $min = null, string|Carbon|Number|Text|float|int|null $max = null): static
    {
        return app(self::class, ['min' => $min, 'max' => $max]);
    }

    public function toText(string $delimeter = '...'): Text
    {
        return Text::make(Text::make($this->min)->toString().$delimeter.Text::make($this->max)->toString());
    }

    private function parseValue(string|Carbon|Number|Text|float|int|null $value): Carbon|Number|Text|null
    {
        return match (true) {
            is_null($value) => null,
            $value instanceof Contracts\Primative => $value,
            $value instanceof Carbon => $value,
            filter_var($value, FILTER_VALIDATE_INT) !== false => Number::make($value),
            filter_var($value, FILTER_VALIDATE_FLOAT) !== false => Number::make($value),
            is_string($value) => Carbon::parse($value),
            default => throw new InvalidArgumentException('Value cannot be converted to Interval.')
        };
    }
}
