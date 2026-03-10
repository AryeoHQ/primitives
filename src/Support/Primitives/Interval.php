<?php

declare(strict_types=1);

namespace Support\Primitives;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use Support\Primitives\Casts\AsInterval;

final class Interval implements Castable, Contracts\Primitive
{
    use Macroable;

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

    public static function make(string|CarbonInterface|CarbonPeriod|Number|Text|float|int|null $min = null, string|CarbonInterface|CarbonPeriod|Number|Text|float|int|null $max = null): static
    {
        return app(self::class, ['min' => $min, 'max' => $max]);
    }

    public function toText(string $delimiter = '...'): Text
    {
        $min = match (true) {
            $this->min === null => '',
            $this->min instanceof CarbonInterface => $this->min->toISOString(),
            default => (string) $this->min,
        };

        $max = match (true) {
            $this->max === null => '',
            $this->max instanceof CarbonInterface => $this->max->toISOString(),
            default => (string) $this->max,
        };

        return Text::make($min.$delimiter.$max);
    }

    public function jsonSerialize(): string
    {
        return (string) $this;
    }

    public function __toString(): string
    {
        return $this->toText()->toString();
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
