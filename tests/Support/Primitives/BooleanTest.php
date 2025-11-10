<?php

namespace Tests\Support\Primitives;

use Tests\TestCase;
use Support\Primitives\Text;
use Support\Primitives\Number;
use Support\Primitives\Boolean;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Boolean::class)]
class BooleanTest extends TestCase
{
    #[Test]
    public function boolean_can_be_created(): void
    {
        $boolean1 = Boolean::make(true);
        $boolean2 = Boolean::make(false);

        $this->assertTrue($boolean1);
        $this->assertFalse($boolean2);
    }

    #[Test]
    public function boolean_can_be_created_from_string(): void
    {
        $boolean1 = Boolean::make('true');
        $boolean2 = Boolean::make('false');

        $this->assertTrue($boolean1);
        $this->assertFalse($boolean2);
    }

    #[Test]
    public function boolean_can_be_created_from_integer(): void
    {
        $boolean1 = Boolean::make(1);
        $boolean2 = Boolean::make(0);

        $this->assertTrue($boolean1);
        $this->assertFalse($boolean2);
    }

    #[Test]
    public function boolean_can_be_created_from_primitive(): void
    {
        $boolean1 = Boolean::make(Boolean::make(true));
        $boolean2 = Boolean::make(Boolean::make(false));

        $this->assertTrue($boolean1);
        $this->assertFalse($boolean2);
    }

    #[Test]
    public function boolean_can_be_created_from_number(): void
    {
        $boolean1 = Boolean::make(Number::make(1));
        $boolean2 = Boolean::make(Number::make(0));

        $this->assertTrue($boolean1);
        $this->assertFalse($boolean2);
    }

    #[Test]
    public function boolean_can_be_created_from_text(): void
    {
        $boolean1 = Boolean::make(Text::make('true'));
        $boolean2 = Boolean::make(Text::make('false'));
        
        $this->assertTrue($boolean1);
        $this->assertFalse($boolean2);
    }

    #[Test]
    public function boolean_can_be_casted(): void
    {
        $model = new class extends \Illuminate\Database\Eloquent\Model
        {
            public $attributes = [
                'is_admin' => 'true',
            ];

            protected $casts = [
                'is_admin' => Boolean::class,
            ];
        };

        $this->assertTrue($model->is_admin);
    }
}
