<?php

namespace Tests\Support\Primitives;

use PHPUnit\Framework\Attributes\CoversClass;
use Support\Primitives\Boolean;
use Support\Primitives\Contracts\Primative;
use Support\Primitives\Number;
use Support\Primitives\Text;
use Tests\TestCase;

#[CoversClass(Boolean::class)]
class BooleanTest extends TestCase
{
    public function test_boolean_can_be_created(): void
    {
        $boolean = Boolean::make(true);
        $this->assertInstanceOf(Boolean::class, $boolean);
        $this->assertInstanceOf(Primative::class, $boolean);
        $this->assertEquals(true, $boolean->value);
    }

    public function test_boolean_can_be_inverted(): void
    {
        $boolean = Boolean::make(true);
        $this->assertEquals(false, $boolean->inverse()->value);
    }

    public function test_boolean_can_be_created_from_string(): void
    {
        $boolean = Boolean::make('true');
        $this->assertEquals(true, $boolean->value);
    }

    public function test_boolean_can_be_created_from_integer(): void
    {
        $boolean = Boolean::make(1);
        $this->assertEquals(true, $boolean->value);
    }

    public function test_boolean_can_be_created_from_primative(): void
    {
        $boolean = Boolean::make(new Boolean(true));
        $this->assertEquals(true, $boolean->value);
    }

    public function test_boolean_can_be_created_from_number(): void
    {
        $boolean = Boolean::make(new Number(1));
        $this->assertEquals(true, $boolean->value);
    }

    public function test_boolean_can_be_created_from_text(): void
    {
        $boolean = Boolean::make(new Text('true'));
        $this->assertEquals(true, $boolean->value);
    }
}
