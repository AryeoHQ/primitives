<?php

namespace Tests\Support\Primitives;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PHPUnit\Framework\Attributes\CoversClass;
use Support\Primitives\Interval;
use Support\Primitives\Number;
use Support\Primitives\Text;
use Tests\TestCase;

#[CoversClass(Interval::class)]
class IntervalTest extends TestCase
{
    public function test_interval_can_be_created(): void
    {
        $interval = Interval::make(Carbon::parse('2025-01-01'), Carbon::parse('2025-01-31'));
        $this->assertInstanceOf(Interval::class, $interval);
        $this->assertEquals(Carbon::parse('2025-01-01'), $interval->min);
        $this->assertEquals(Carbon::parse('2025-01-31'), $interval->max);
    }

    public function test_interval_can_be_created_from_carbon_period(): void
    {
        $interval = Interval::make(CarbonPeriod::create('2025-01-01', '2025-01-31'));
        $this->assertInstanceOf(Interval::class, $interval);
        $this->assertEquals(Carbon::parse('2025-01-01'), $interval->min);
        $this->assertEquals(Carbon::parse('2025-01-31'), $interval->max);
    }

    public function test_interval_can_be_created_from_text(): void
    {
        $interval = Interval::make(Text::make('2025-01-01'), Text::make('2025-01-31'));
        $this->assertInstanceOf(Interval::class, $interval);
        $this->assertInstanceOf(Text::class, $interval->min);
        $this->assertInstanceOf(Text::class, $interval->max);
        $this->assertEquals('2025-01-01', $interval->min->value);
        $this->assertEquals('2025-01-31', $interval->max->value);
    }

    public function test_interval_can_be_created_from_number(): void
    {
        $interval = Interval::make(Number::make(10), Number::make(20));
        $this->assertInstanceOf(Interval::class, $interval);
        $this->assertInstanceOf(Number::class, $interval->min);
        $this->assertInstanceOf(Number::class, $interval->max);
        $this->assertEquals(10, $interval->min->value);
        $this->assertEquals(20, $interval->max->value);
    }
}
