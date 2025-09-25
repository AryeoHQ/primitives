<?php

namespace Tests\Support\Primitives;

use Illuminate\Support\Carbon;
use Illuminate\Support\Stringable;
use PHPUnit\Framework\Attributes\CoversClass;
use Support\Primitives\Contracts\Primative;
use Support\Primitives\Interval;
use Support\Primitives\Text;
use Tests\TestCase;

#[CoversClass(Text::class)]
class TextTest extends TestCase
{
    public function test_text_can_be_created(): void
    {
        $text = Text::make('Hello World');
        $this->assertInstanceOf(Text::class, $text);
        $this->assertInstanceOf(Primative::class, $text);
        $this->assertEquals('Hello World', $text->value);
    }

    public function test_text_is_insance_of_stringable(): void
    {
        $text = Text::make('Hello World');
        $this->assertInstanceOf(Stringable::class, $text);
    }

    public function test_text_can_be_created_from_primative(): void
    {
        $text = Text::make(Text::make('Hello World'));
        $this->assertEquals('Hello World', $text->value);
    }

    public function test_text_can_be_created_from_string(): void
    {
        $text = Text::make('Hello World');
        $this->assertEquals('Hello World', $text->value);
    }

    public function test_text_can_be_converted_to_interval(): void
    {
        $interval = Text::make('2025-01-01...2025-01-31')->toInterval();

        $this->assertInstanceOf(Interval::class, $interval);
        $this->assertEquals(Carbon::parse('2025-01-01'), $interval->min);
        $this->assertEquals(Carbon::parse('2025-01-31'), $interval->max);
    }

    public function test_text_can_be_converted_to_interval_with_custom_delimeter(): void
    {
        $interval = Text::make('2025-01-01||2025-01-31')->toInterval('||');

        $this->assertInstanceOf(Interval::class, $interval);
        $this->assertEquals(Carbon::parse('2025-01-01'), $interval->min);
        $this->assertEquals(Carbon::parse('2025-01-31'), $interval->max);
    }

    public function test_text_can_be_converted_to_interval_with_single_value(): void
    {
        $interval = Text::make('2025-01-01')->toInterval();

        $this->assertInstanceOf(Interval::class, $interval);
        $this->assertEquals(Carbon::parse('2025-01-01'), $interval->min);
        $this->assertEquals(null, $interval->max);
    }

    public function test_text_can_be_casted(): void
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
