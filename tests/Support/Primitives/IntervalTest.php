<?php

namespace Tests\Support\Primitives;

use Illuminate\Support\Facades\Date;
use Carbon\Carbon;
use Tests\TestCase;
use Carbon\CarbonPeriod;
use Support\Primitives\Text;
use Support\Primitives\Number;
use Support\Primitives\Interval;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Interval::class)]
class IntervalTest extends TestCase
{
    #[Test]
    public function interval_can_be_created(): void
    {
        $interval = Interval::make(Date::parse('2025-01-01'), Date::parse('2025-01-31'));

        $this->assertInstanceOf(Interval::class, $interval);
        $this->assertEquals(Date::parse('2025-01-01'), $interval->min);
        $this->assertEquals(Date::parse('2025-01-31'), $interval->max);
    }

    #[Test]
    public function interval_can_be_created_from_carbon_period(): void
    {
        $interval = Interval::make(CarbonPeriod::create('2025-01-01', '2025-01-31'));

        $this->assertInstanceOf(Interval::class, $interval);
        $this->assertEquals(Date::parse('2025-01-01'), $interval->min);
        $this->assertEquals(Date::parse('2025-01-31'), $interval->max);
    }

    #[Test]
    public function interval_can_be_created_from_text(): void
    {
        $interval = Interval::make(Text::make('2025-01-01'), Text::make('2025-01-31'));
        
        $this->assertInstanceOf(Text::class, $interval->min);
        $this->assertInstanceOf(Text::class, $interval->max);
        $this->assertEquals('2025-01-01', $interval->min->value);
        $this->assertEquals('2025-01-31', $interval->max->value);
    }

    #[Test]
    public function interval_can_be_created_from_number(): void
    {
        $interval = Interval::make(Number::make(10), Number::make(20));

        $this->assertInstanceOf(Interval::class, $interval);
        $this->assertInstanceOf(Number::class, $interval->min);
        $this->assertInstanceOf(Number::class, $interval->max);
        $this->assertEquals(10, $interval->min->value);
        $this->assertEquals(20, $interval->max->value);
    }

    #[Test]
    public function interval_can_be_converted_to_text(): void
    {
        $interval = Interval::make(10, 20);
        
        $this->assertInstanceOf(Text::class, $interval->toText());
        $this->assertEquals('10...20', $interval->toText()->value);
    }

    #[Test]
    public function interval_can_be_converted_to_text_with_custom_delimeter(): void
    {
        $interval = Interval::make(10, 20);
        
        $this->assertInstanceOf(Text::class, $interval->toText('||'));
        $this->assertEquals('10||20', $interval->toText('||')->value);
    }

    #[Test]
    public function interval_can_be_casted(): void
    {
        $model = new class extends \Illuminate\Database\Eloquent\Model
        {
            public $attributes = [
                'date' => '2025-01-01...2025-01-31',
            ];

            protected $casts = [
                'date' => Interval::class,
            ];
        };

        $this->assertInstanceOf(Interval::class, $model->date);
        $this->assertEquals(Date::parse('2025-01-01'), $model->date->min);
        $this->assertEquals(Date::parse('2025-01-31'), $model->date->max);
    }
}
