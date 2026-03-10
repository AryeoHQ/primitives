<?php

declare(strict_types=1);

namespace Support\Primitives;

enum Direction: string
{
    case Asc = 'asc';
    case Desc = 'desc';

    public function toPrefix(): Text
    {
        return Text::make(match ($this) {
            self::Desc => '-',
            default => '',
        });
    }
}
