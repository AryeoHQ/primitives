<?php

declare(strict_types=1);

namespace Support\Primitives;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Date;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Interval::class)]
class IntervalTest extends TestCase
{
    #[Test]
    public function interval_can_be_created(): void
    {
        $interval = Interval::make(Date::parse('2025-01-01'), \Illuminate\Support\Facades\Date::parse('2025-01-31'));

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
        $this->assertSame('2025-01-01', $interval->min->toString());
        $this->assertSame('2025-01-31', $interval->max->toString());
    }

    #[Test]
    public function interval_can_be_created_from_number(): void
    {
        $interval = Interval::make(Number::make(10), Number::make(20));

        $this->assertInstanceOf(Interval::class, $interval);
        $this->assertInstanceOf(Number::class, $interval->min);
        $this->assertInstanceOf(Number::class, $interval->max);
        $this->assertSame('10', $interval->min->toString());
        $this->assertSame('20', $interval->max->toString());
    }

    #[Test]
    public function interval_can_be_converted_to_text(): void
    {
        $interval = Interval::make(10, 20);

        $this->assertInstanceOf(Text::class, $interval->toText());
        $this->assertSame('10...20', $interval->toText()->toString());
    }

    #[Test]
    public function interval_can_be_converted_to_text_with_custom_delimeter(): void
    {
        $interval = Interval::make(10, 20);

        $this->assertInstanceOf(Text::class, $interval->toText('||'));
        $this->assertSame('10||20', $interval->toText('||')->toString());
    }

    #[Test]
    public function interval_can_be_json_serialized(): void
    {
        $interval = Interval::make(1, 100);

        $this->assertSame('"1...100"', json_encode($interval));
    }

    #[Test]
    public function interval_can_be_cast_to_string(): void
    {
        $interval = Interval::make(1, 100);

        $this->assertSame('1...100', $interval->toString());
        $this->assertSame($interval->toString(), (string) $interval);

        $openMin = Interval::make(null, 100);
        $this->assertSame('...100', $openMin->toString());
        $this->assertSame($openMin->toString(), (string) $openMin);

        $openMax = Interval::make(5, null);
        $this->assertSame('5...', $openMax->toString());
        $this->assertSame($openMax->toString(), (string) $openMax);
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

        $this->assertInstanceOf(Interval::class, $model->date); // @phpstan-ignore property.notFound
        $this->assertEquals(Date::parse('2025-01-01'), $model->date->min); // @phpstan-ignore property.notFound
        $this->assertEquals(Date::parse('2025-01-31'), $model->date->max); // @phpstan-ignore property.notFound
    }
}
