<?php

declare(strict_types=1);

namespace Support\Primitives;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Date;
use InvalidArgumentException;
use Support\Primitives\Casts\AsInterval;

final class Interval extends Primitive
{
    public readonly string|CarbonInterface|Number|Text|float|int|null $min;

    public readonly string|CarbonInterface|Number|Text|float|int|null $max;

    public function __construct(string|CarbonInterface|CarbonPeriod|Number|Text|float|int|null $min = null, string|CarbonInterface|CarbonPeriod|Number|Text|float|int|null $max = null)
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

    public function toText(string $delimiter = '...'): Text
    {
        return Text::make(Text::make($this->min)->toString().$delimiter.Text::make($this->max)->toString());
    }

    private function parseValue(string|CarbonInterface|Number|Text|float|int|null $value): CarbonInterface|Number|Text|null
    {
        return match (true) {
            is_null($value) => null,
            $value instanceof Contracts\Primitive => $value,
            $value instanceof CarbonInterface => $value,
            filter_var($value, FILTER_VALIDATE_INT) !== false => Number::make($value),
            filter_var($value, FILTER_VALIDATE_FLOAT) !== false => Number::make($value),
            is_string($value) => Date::parse($value),
            default => throw new InvalidArgumentException('Value cannot be converted to Interval.')
        };
    }
}
