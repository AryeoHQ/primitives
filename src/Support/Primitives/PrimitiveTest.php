<?php

namespace Support\Primitives;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Primitive::class)]
class PrimitiveTest extends TestCase
{
    #[Test]
    public function can_make_with_single_parameter(): void
    {
        $number = Number::make(10);

        $this->assertInstanceOf(Number::class, $number);
        $this->assertEquals(10, $number->value);
    }

    #[Test]
    public function can_make_with_multiple_parameters(): void
    {
        $interval = Interval::make(10, 20);

        $this->assertInstanceOf(Interval::class, $interval);
        $this->assertEquals(10, $interval->min->value);
        $this->assertEquals(20, $interval->max->value);
    }
}
