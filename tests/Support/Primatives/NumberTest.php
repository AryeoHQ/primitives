<?php

namespace Tests\Support\Primitives;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use Support\Primitives\Contracts\Primative;
use Support\Primitives\Number;
use Support\Primitives\Text;
use Tests\TestCase;

#[CoversClass(Number::class)]
class NumberTest extends TestCase
{
    public function test_number_can_be_created(): void
    {
        $number = Number::make(10);
        $this->assertInstanceOf(Number::class, $number);
        $this->assertInstanceOf(Primative::class, $number);
        $this->assertEquals(10, $number->value);
    }

    public function test_number_can_be_created_from_string(): void
    {
        $number = Number::make('10');
        $this->assertEquals(10, $number->value);
    }

    public function test_number_can_be_created_from_integer(): void
    {
        $number = Number::make(10);
        $this->assertEquals(10, $number->value);
    }

    public function test_number_can_be_created_from_primative(): void
    {
        $number = Number::make(new Number(10));
        $this->assertEquals(10, $number->value);
    }

    public function test_number_can_be_created_from_text(): void
    {
        $number = Number::make(new Text('10'));
        $this->assertEquals(10, $number->value);
    }

    public function test_number_can_be_added(): void
    {
        $number = Number::make(10);
        $this->assertEquals(20, $number->add(10)->value);
    }

    public function test_number_can_be_subtracted(): void
    {
        $number = Number::make(10);
        $this->assertEquals(0, $number->subtract(10)->value);
    }

    public function test_number_can_be_multiplied(): void
    {
        $number = Number::make(10);
        $this->assertEquals(100, $number->multiply(10)->value);
    }

    public function test_number_can_be_divided(): void
    {
        $number = Number::make(10);
        $this->assertEquals(1, $number->divide(10)->value);
    }

    public function test_number_can_be_rounded(): void
    {
        $number = Number::make(10.5);
        $this->assertEquals(11, $number->round()->value);
    }

    public function test_number_can_be_converted_to_decimal(): void
    {
        $number = Number::make(10.5);
        $this->assertEquals(10.50, $number->toDecimal()->value);
    }

    public function test_number_can_be_converted_to_integer(): void
    {
        $number = Number::make(10.4);
        $this->assertEquals(10, $number->toInteger()->value);
    }

    public function test_it_throws_an_error_if_the_value_cannot_be_converted_to_a_number(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Number::make('invalid');
    }

    public function test_number_can_be_casted(): void
    {
        $model = new class extends \Illuminate\Database\Eloquent\Model
        {
            public $attributes = [
                'age' => '10',
            ];

            protected $casts = [
                'age' => Number::class,
            ];
        };

        $this->assertInstanceOf(Number::class, $model->age);
        $this->assertEquals(10, $model->age->value);
    }
}
