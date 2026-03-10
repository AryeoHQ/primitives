<?php

namespace Tests\Support\Primitives;

use Tests\TestCase;
use Support\Primitives\Sort;
use Support\Primitives\Text;
use Support\Primitives\Direction;
use Support\Primitives\Casts\AsSort;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Sort::class)]
class SortTest extends TestCase
{

    #[Test]
    public function sort_can_be_created_with_direction_enum(): void
    {
        $sort = Sort::make('created_at', Direction::Desc);

        $this->assertInstanceOf(Sort::class, $sort);
        $this->assertEquals('created_at', $sort->field->toString());
        $this->assertSame(Direction::Desc, $sort->direction);
    }

    #[Test]
    public function sort_can_be_created_with_string_direction(): void
    {
        $sort = Sort::make('created_at', 'desc');

        $this->assertEquals('created_at', $sort->field->toString());
        $this->assertSame(Direction::Desc, $sort->direction);
    }

    #[Test]
    public function sort_defaults_to_ascending(): void
    {
        $sort = Sort::make('created_at');

        $this->assertSame(Direction::Asc, $sort->direction);
    }

    #[Test]
    public function sort_can_be_created_from_prefix_string(): void
    {
        $sort = Sort::make('-created_at');

        $this->assertEquals('created_at', $sort->field->toString());
        $this->assertSame(Direction::Desc, $sort->direction);
    }

    #[Test]
    public function sort_can_be_created_from_prefix_string_ascending(): void
    {
        $sort = Sort::make('created_at');

        $this->assertEquals('created_at', $sort->field->toString());
        $this->assertSame(Direction::Asc, $sort->direction);
    }

    #[Test]
    public function sort_can_be_created_from_text(): void
    {
        $sort = Sort::make(Text::make('created_at'), Direction::Asc);

        $this->assertInstanceOf(Text::class, $sort->field);
        $this->assertEquals('created_at', $sort->field->toString());
    }

    #[Test]
    public function sort_can_be_created_from_sort(): void
    {
        $sort = Sort::make('created_at', Direction::Desc);
        $same = Sort::make($sort);

        $this->assertSame($sort, $same);
    }

    #[Test]
    public function sort_throws_on_empty_field(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Sort::make('');
    }

    #[Test]
    public function sort_throws_on_whitespace_field(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Sort::make('  ');
    }

    #[Test]
    public function sort_throws_on_invalid_direction(): void
    {
        $this->expectException(\ValueError::class);

        Sort::make('created_at', 'invalid');
    }

    #[Test]
    public function sort_can_be_json_serialized(): void
    {
        $sort = Sort::make('created_at', Direction::Desc);

        $this->assertEquals(
            '"-created_at"',
            json_encode($sort),
        );
    }

    #[Test]
    public function sort_to_text_includes_direction_prefix(): void
    {
        $desc = Sort::make('created_at', Direction::Desc);
        $asc = Sort::make('created_at', Direction::Asc);

        $this->assertInstanceOf(Text::class, $desc->toText());
        $this->assertEquals('-created_at', $desc->toText()->toString());
        $this->assertEquals('created_at', $asc->toText()->toString());
    }

    #[Test]
    public function sort_to_string_includes_direction_prefix(): void
    {
        $desc = Sort::make('created_at', Direction::Desc);
        $asc = Sort::make('created_at', Direction::Asc);

        $this->assertEquals('-created_at', (string) $desc);
        $this->assertEquals('created_at', (string) $asc);
    }

    #[Test]
    public function sort_implements_primitive_contract(): void
    {
        $sort = Sort::make('created_at');

        $this->assertInstanceOf(\Support\Primitives\Contracts\Primitive::class, $sort);
    }

    #[Test]
    public function sort_cast_uses_as_sort(): void
    {
        $this->assertSame(AsSort::class, Sort::castUsing([]));
    }

    #[Test]
    public function sort_can_be_casted(): void
    {
        $model = new class extends \Illuminate\Database\Eloquent\Model
        {
            public $attributes = [
                'sort' => '-created_at',
            ];

            protected $casts = [
                'sort' => Sort::class,
            ];
        };

        $this->assertInstanceOf(Sort::class, $model->sort);
        $this->assertEquals('created_at', $model->sort->field->toString());
        $this->assertSame(Direction::Desc, $model->sort->direction);
    }

    #[Test]
    public function sort_cast_can_set_value(): void
    {
        $model = new class extends \Illuminate\Database\Eloquent\Model
        {
            public $attributes = [];

            protected $casts = [
                'sort' => Sort::class,
            ];
        };

        $model->sort = Sort::make('created_at', Direction::Desc);

        $this->assertEquals(
            '-created_at',
            $model->getAttributes()['sort'],
        );
    }

    #[Test]
    public function sort_cast_can_set_ascending_value(): void
    {
        $model = new class extends \Illuminate\Database\Eloquent\Model
        {
            public $attributes = [];

            protected $casts = [
                'sort' => Sort::class,
            ];
        };

        $model->sort = Sort::make('created_at', Direction::Asc);

        $this->assertEquals(
            'created_at',
            $model->getAttributes()['sort'],
        );
    }

    #[Test]
    public function sort_cast_handles_null(): void
    {
        $model = new class extends \Illuminate\Database\Eloquent\Model
        {
            public $attributes = [
                'sort' => null,
            ];

            protected $casts = [
                'sort' => Sort::class,
            ];
        };

        $this->assertNull($model->sort);
    }

    #[Test]
    public function sort_cast_can_set_null(): void
    {
        $model = new class extends \Illuminate\Database\Eloquent\Model
        {
            public $attributes = [
                'sort' => '-created_at',
            ];

            protected $casts = [
                'sort' => Sort::class,
            ];
        };

        $model->sort = null;

        $this->assertNull($model->getAttributes()['sort']);
    }

    #[Test]
    public function sort_prefix_overrides_explicit_direction(): void
    {
        $sort = Sort::make('-created_at', Direction::Asc);

        $this->assertSame(Direction::Desc, $sort->direction);
        $this->assertEquals('created_at', $sort->field->toString());
    }

    #[Test]
    public function sort_throws_on_bare_dash(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Sort::make('-');
    }

    #[Test]
    public function sort_cast_can_set_raw_string(): void
    {
        $model = new class extends \Illuminate\Database\Eloquent\Model
        {
            public $attributes = [];

            protected $casts = [
                'sort' => Sort::class,
            ];
        };

        $model->sort = '-created_at';

        $this->assertEquals(
            '-created_at',
            $model->getAttributes()['sort'],
        );
    }

    #[Test]
    public function sort_roundtrips_through_text(): void
    {
        $original = Sort::make('created_at', Direction::Desc);
        $roundtripped = Sort::make($original->toText());

        $this->assertEquals($original->field->toString(), $roundtripped->field->toString());
        $this->assertSame($original->direction, $roundtripped->direction);
    }
}
