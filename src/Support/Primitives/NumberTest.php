<?php

declare(strict_types=1);

namespace Support\Primitives;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Support\Primitives\Contracts\Primitive;
use Tests\TestCase;

#[CoversClass(Number::class)]
class NumberTest extends TestCase
{
    #[Test]
    public function number_can_be_created(): void
    {
        $number = Number::make(10);

        $this->assertInstanceOf(Number::class, $number);
        $this->assertInstanceOf(Primitive::class, $number);
        $this->assertEquals(10, $number->value);
    }

    #[Test]
    public function number_can_be_created_from_string(): void
    {
        $number = Number::make('10');

        $this->assertEquals(10, $number->value);
    }

    #[Test]
    public function number_can_be_created_from_integer(): void
    {
        $number = Number::make(10);

        $this->assertEquals(10, $number->value);
    }

    #[Test]
    public function number_can_be_created_from_primitive(): void
    {
        $number = Number::make(new Number(10));

        $this->assertEquals(10, $number->value);
    }

    #[Test]
    public function number_can_be_created_from_text(): void
    {
        $number = Number::make(new Text('10'));

        $this->assertEquals(10, $number->value);
    }

    #[Test]
    public function number_can_be_added(): void
    {
        $number = Number::make(10);

        $this->assertEquals(20, $number->add(10)->value);
    }

    #[Test]
    public function number_can_be_subtracted(): void
    {
        $number = Number::make(10);

        $this->assertEquals(0, $number->subtract(10)->value);
    }

    #[Test]
    public function number_can_be_multiplied(): void
    {
        $number = Number::make(10);

        $this->assertEquals(100, $number->multiply(10)->value);
    }

    #[Test]
    public function number_can_be_divided(): void
    {
        $number = Number::make(10);

        $this->assertEquals(1, $number->divide(10)->value);
    }

    #[Test]
    public function number_can_be_rounded(): void
    {
        $number = Number::make(10.5);

        $this->assertEquals(11, $number->round()->value);
    }

    #[Test]
    public function number_can_be_converted_to_decimal(): void
    {
        $number = Number::make(10.5);

        $this->assertEquals(10.50, $number->toDecimal()->value);
    }

    #[Test]
    public function number_can_be_converted_to_integer(): void
    {
        $number = Number::make(10.4);

        $this->assertEquals(10, $number->toInteger()->value);
    }

    #[Test]
    public function it_throws_an_error_if_the_value_cannot_be_converted_to_a_number(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Number::make('invalid');
    }

    #[Test]
    public function number_can_be_json_serialized(): void
    {
        $this->assertSame('42', json_encode(Number::make(42)));
        $this->assertSame('3.14', json_encode(Number::make(3.14)));
    }

    #[Test]
    public function number_can_be_cast_to_string(): void
    {
        $integer = Number::make(42);
        $this->assertSame('42', $integer->toString());
        $this->assertSame($integer->toString(), (string) $integer);

        $float = Number::make(3.14);
        $this->assertSame('3.14', $float->toString());
        $this->assertSame($float->toString(), (string) $float);
    }

    #[Test]
    public function number_can_be_casted(): void
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

        $this->assertInstanceOf(Number::class, $model->age); // @phpstan-ignore property.notFound
        $this->assertEquals(10, $model->age->value); // @phpstan-ignore property.notFound
    }
}
