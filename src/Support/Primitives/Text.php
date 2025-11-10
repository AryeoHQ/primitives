<?php

declare(strict_types=1);

namespace Support\Primitives;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Support\Stringable;
use InvalidArgumentException;
use Support\Primitives\Casts\AsText;
use Support\Primitives\Contracts\Primitive;

final class Text extends Stringable implements Castable, Primitive
{
    public function __construct(int|float|string|bool|Text|Number $value)
    {
        $this->value = $this->parseValue($value);

        parent::__construct($this->value);
    }

    /**
     * @return class-string<\Illuminate\Contracts\Database\Eloquent\CastsAttributes<Text, Text|mixed>>
     */
    public static function castUsing(array $arguments): string
    {
        return AsText::class;
    }

    final public static function make(mixed $value): static
    {
        return match ($value instanceof self) {
            true => $value,
            false => app(self::class, ['value' => $value]),
        };
    }

    public static function of(string $string): static
    {
        return new static($string);
    }

    public function toInterval(string $delimiter = '...'): Interval
    {
        if ($delimiter === '') {
            throw new InvalidArgumentException('Delimiter cannot be empty.');
        }

        $intervals = collect(explode($delimiter, $this->value))
            ->map(fn ($value) => $value === '' ? null : $value);

        if ($intervals->count() === 1) {
            return Interval::make($intervals->first());
        }

        return Interval::make($intervals->first(), $intervals->last());
    }

    private function parseValue(int|float|string|bool|Text|Number $value): string
    {
        return match (true) {
            is_string($value) => $value,
            $value instanceof Number => (string) $value->value,
            default => (string) $value
        };
    }
}
