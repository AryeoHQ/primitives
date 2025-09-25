<?php

namespace Support\Primitives;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Support\Stringable;
use InvalidArgumentException;
use Support\Primitives\Contracts\Primative;

final class Text extends Stringable implements Castable, Primative
{
    public function __construct(int|float|string|Text|Number|Boolean $value)
    {
        $this->value = $this->parseValue($value);

        parent::__construct($this->value);
    }

    /**
     * @return class-string<\Illuminate\Contracts\Database\Eloquent\CastsAttributes<Text, Text|mixed>>
     */
    public static function castUsing(array $arguments): string
    {
        return \Support\Primitives\Casts\AsText::class;
    }

    final public static function make(mixed $value): static
    {
        /** @var static */
        return app(self::class, ['value' => $value]);
    }

    public static function of(string $string): static
    {
        return new static($string);
    }

    public function toInterval(string $delimeter = '...'): Interval
    {
        if ($delimeter === '') {
            throw new InvalidArgumentException('Delimiter cannot be empty.');
        }

        $intervals = collect(explode($delimeter, $this->value))
            ->map(fn ($value) => $value === '' ? null : $value);

        if ($intervals->count() === 1) {
            return Interval::make($intervals->first());
        }

        return Interval::make($intervals->first(), $intervals->last());
    }

    private function parseValue(int|float|string|Text|Number|Boolean $value): string
    {
        return match (true) {
            is_string($value) => $value,
            $value instanceof Number || $value instanceof Boolean => (string) $value->value,
            default => (string) $value
        };
    }
}
