<?php

declare(strict_types=1);

namespace Support\Primitives;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use JsonSerializable;
use Support\Primitives\Casts\AsSort;

final class Sort implements Castable, Contracts\Primitive, JsonSerializable
{
    use Macroable;

    public readonly Text $field;

    public readonly Direction $direction;

    public function __construct(string|Text|Sort $field, string|Direction|null $direction = null)
    {
        if ($field instanceof Sort) {
            $this->field = $field->field;
            $this->direction = $field->direction;

            return;
        }

        $field = $field instanceof Text ? $field : Text::make($field);

        $this->direction = match (true) {
            $field->startsWith('-') => Direction::Desc,
            $direction instanceof Direction => $direction,
            is_string($direction) => Direction::from($direction),
            default => Direction::Asc,
        };

        $this->field = $field->ltrim('-')->when(
            fn (Text $field) => $field->trim()->isEmpty(),
            fn () => throw new InvalidArgumentException('Field cannot be empty.'),
        );
    }

    public static function make(string|Text|Sort $field, string|Direction|null $direction = null): static
    {
        return match ($field instanceof self) {
            true => $field,
            false => app(self::class, ['field' => $field, 'direction' => $direction]),
        };
    }

    /**
     * @return class-string<CastsAttributes<Sort, Sort|mixed>>
     */
    public static function castUsing(array $arguments): string
    {
        return AsSort::class;
    }

    public function jsonSerialize(): string
    {
        return (string) $this;
    }

    public function toText(): Text
    {
        return $this->direction->toPrefix()->append($this->field->toString());
    }

    public function __toString(): string
    {
        return $this->toText()->toString();
    }
}
