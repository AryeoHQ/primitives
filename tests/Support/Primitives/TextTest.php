<?php

namespace Tests\Support\Primitives;

use Illuminate\Support\Facades\Date;
use Tests\TestCase;
use Support\Primitives\Text;
use Support\Primitives\Interval;
use Illuminate\Support\Stringable;
use PHPUnit\Framework\Attributes\Test;
use Support\Primitives\Contracts\Primitive;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Text::class)]
class TextTest extends TestCase
{
    #[Test]
    public function text_can_be_created(): void
    {
        $text = Text::make('Hello World');

        $this->assertInstanceOf(Text::class, $text);
        $this->assertInstanceOf(Primitive::class, $text);
        $this->assertEquals('Hello World', $text->value);
    }

    #[Test]
    public function text_is_insance_of_stringable(): void
    {
        $text = Text::make('Hello World');

        $this->assertInstanceOf(Stringable::class, $text);
    }

    #[Test]
    public function text_can_be_created_from_primitive(): void
    {
        $text = Text::make(Text::make('Hello World'));

        $this->assertEquals('Hello World', $text->value);
    }

    #[Test]
    public function text_can_be_created_from_string(): void
    {
        $text = Text::make('Hello World');

        $this->assertEquals('Hello World', $text->value);
    }

    #[Test]
    public function text_can_be_converted_to_interval(): void
    {
        $interval = Text::make('2025-01-01...2025-01-31')->toInterval();

        $this->assertInstanceOf(Interval::class, $interval);
        $this->assertEquals(Date::parse('2025-01-01'), $interval->min);
        $this->assertEquals(Date::parse('2025-01-31'), $interval->max);
    }

    #[Test]
    public function text_can_be_converted_to_interval_with_custom_delimeter(): void
    {
        $interval = Text::make('2025-01-01||2025-01-31')->toInterval('||');

        $this->assertInstanceOf(Interval::class, $interval);
        $this->assertEquals(Date::parse('2025-01-01'), $interval->min);
        $this->assertEquals(Date::parse('2025-01-31'), $interval->max);
    }

    #[Test]
    public function text_can_be_converted_to_interval_with_custom_delimeter_and_multiple_of_same_delimeter(): void
    {
        $interval = Text::make('2025-01-01||2025-01-31||2025-02-15')->toInterval('||');

        $this->assertInstanceOf(Interval::class, $interval);
        $this->assertEquals(Date::parse('2025-01-01'), $interval->min);
        $this->assertEquals(Date::parse('2025-02-15'), $interval->max);
    }

    #[Test]
    public function text_can_be_converted_to_interval_with_single_value(): void
    {
        $interval = Text::make('2025-01-01')->toInterval();

        $this->assertInstanceOf(Interval::class, $interval);
        $this->assertEquals(Date::parse('2025-01-01'), $interval->min);
        $this->assertEquals(null, $interval->max);
    }

    #[Test]
    public function text_can_be_converted_to_interval_with_no_minimum(): void
    {
        $interval = Text::make('...2025-01-01')->toInterval();

        $this->assertInstanceOf(Interval::class, $interval);
        $this->assertNull($interval->min);
        $this->assertEquals(Date::parse('2025-01-01'), $interval->max);

    }

    #[Test]
    public function text_can_be_converted_to_interval_with_no_maximum(): void
    {
        $interval = Text::make('2025-01-01...')->toInterval();

        $this->assertInstanceOf(Interval::class, $interval);
        $this->assertEquals(Date::parse('2025-01-01'), $interval->min);
        $this->assertNull($interval->max);
    }

    #[Test]
    public function text_can_be_json_serialized(): void
    {
        $this->assertEquals('"hello"', json_encode(Text::make('hello')));
    }

    #[Test]
    public function text_can_be_casted(): void
    {
        $model = new class extends \Illuminate\Database\Eloquent\Model
        {
            public $attributes = [
                'name' => 'John Doe',
            ];

            protected $casts = [
                'name' => Text::class,
            ];
        };

        $this->assertInstanceOf(Text::class, $model->name);
        $this->assertEquals('John Doe', $model->name->value);
    }
}
