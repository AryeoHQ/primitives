<?php

namespace Support\Primitives;

final class Boolean extends Primitive
{
    public readonly bool $value;

    public function __construct(bool|int|string|Text|Number|Boolean $value)
    {
        $this->value = $this->parseValue($value);
    }

    public function inverse(): static
    {
        return self::make(! $this->value);
    }

    private function parseValue(bool|int|string|Text|Number|Boolean $value): bool
    {
        return match (true) {
            $value instanceof Contracts\Primative => (bool) $value->value,
            default => filter_var($value, FILTER_VALIDATE_BOOLEAN)
        };
    }
}
