<?php

namespace Support\Primitives;

use InvalidArgumentException;
use RoundingMode;

final class Number extends Primitive
{
    public readonly int|float $value;

    public function __construct(int|float|string|Text|Number $value)
    {
        $this->value = $this->parseValue($value);
    }

    public function add(int|float|string|Text|Number $value): static
    {
        return self::make($this->value + $this->parseValue($value));
    }

    public function subtract(int|float|string|Text|Number $value): static
    {
        return static::make($this->value - $this->parseValue($value));
    }

    public function multiply(int|float|string|Text|Number $factor): static
    {
        return static::make($this->value * $this->parseValue($factor));
    }

    public function divide(int|float|string|Text|Number $factor): static
    {
        return static::make($this->value / $this->parseValue($factor));
    }

    public function round(int $precision = 0, RoundingMode $mode = RoundingMode::HalfAwayFromZero): static
    {
        return static::make(round($this->value, $precision, $mode));
    }

    private function parseValue(int|float|string|Text|Number $value): int|float
    {
        return match (true) {
            $value instanceof Contracts\Primative => is_numeric($value->value)
                ? (float) $value->value
                : throw new InvalidArgumentException('Primative value must be numeric.'),
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
}
