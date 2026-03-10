<?php

declare(strict_types=1);

namespace Support\Primitives;

use InvalidArgumentException;
use RoundingMode;
use Support\Primitives\Casts\AsNumber;

final class Number extends Primitive
{
    public readonly int|float $value;

    public function __construct(int|float|string|Text|Number $value)
    {
        $this->value = $this->parseValue($value);
    }

    /**
     * @return class-string<\Illuminate\Contracts\Database\Eloquent\CastsAttributes<Number, Number|mixed>>
     */
    public static function castUsing(array $arguments): string
    {
        return AsNumber::class;
    }

    public function add(int|float|string|Text|Number $value): static
    {
        return self::make(bcadd((string) $this->value, (string) $this->parseValue($value), 2));
    }

    public function subtract(int|float|string|Text|Number $value): static
    {
        return static::make(bcsub((string) $this->value, (string) $this->parseValue($value), 2));
    }

    public function multiply(int|float|string|Text|Number $factor): static
    {
        return static::make(bcmul((string) $this->value, (string) $this->parseValue($factor), 2));
    }

    public function divide(int|float|string|Text|Number $factor): static
    {
        return static::make(bcdiv((string) $this->value, (string) $this->parseValue($factor), 2));
    }

    public function round(int $precision = 0, RoundingMode $mode = RoundingMode::HalfAwayFromZero): static
    {
        return static::make(round($this->value, $precision, $mode));
    }

    private function parseValue(int|float|string|Text|Number $value): int|float
    {
        return match (true) {
            $value instanceof Contracts\Primitive => is_numeric($value->value)
                ? (float) $value->value
                : throw new InvalidArgumentException('Primitive value must be numeric.'),
            filter_var($value, FILTER_VALIDATE_INT) !== false => filter_var($value, FILTER_VALIDATE_INT),
            filter_var($value, FILTER_VALIDATE_FLOAT) !== false => filter_var($value, FILTER_VALIDATE_FLOAT),
            default => throw new InvalidArgumentException('Value cannot be converted to Number.')
        };
    }

    public function toDecimal(int $precision = 2): static
    {
        return static::make(number_format($this->value, $precision));
    }

    public function toInteger(): static
    {
        return static::make($this->value)->round();
    }

    public function jsonSerialize(): int|float
    {
        return $this->value;
    }

    public function toString(): string
    {
        return (string) $this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
