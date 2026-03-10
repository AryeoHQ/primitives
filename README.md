# Primitives
A package providing object-oriented wrappers for primitive types, allowing you to encapsulate values and perform transformations on them.

## Installation
```bash
composer require aryeo/primitives
```

## Usage

### Boolean

The `Boolean` class provides an object-oriented wrapper for Boolean values with type-safe operations. Think of it as an enhanced `bool`.

#### Methods

##### `make(mixed $value): static`
Static factory method to create a new Boolean instance.
```php
$bool = Boolean::make(true); // true
$bool = Boolean::make(false); // false
$bool = Boolean::make('true'); // true
$bool = Boolean::make('false'); // false
$bool = Boolean::make(1); // true
$bool = Boolean::make(0); // false
$bool = Boolean::make('yes') // true
$bool = Boolean::make('no') // false
$bool = Boolean::make('on') // true
$bool = Boolean::make('off') // false
```

> [!NOTE]
> `Boolean` will not return an instance of itself as there are no additional operations needed. It will return a native `bool` when `make()` is called.

### Interval

The `Interval` class represents a range between two values, supporting dates, numbers, and text.

#### Methods

##### `make(string|Carbon|CarbonPeriod|Number|Text|float|int|null $min = null, string|Carbon|Number|Text|float|int|null $max = null): static`
Static factory method to create a new Interval instance.
```php
$interval = Interval::make('2023-01-01', '2023-12-31');
$interval = Interval::make(1, 100);
$interval = Interval::make(CarbonPeriod::create('2023-01-01', '2023-12-31'));
```

If a `min` and `max` value are present and the values do not resolve to the same instance type, an `InvalidArgumentException` will be thrown.

### Number

The `Number` class provides mathematical operations and transformations for numeric values.

#### Constructor
```php
new Number(int|float|string|Text|Number $value)
```

#### Methods

##### `add(int|float|string|Text|Number $value): static`
Adds a value to the current number and returns a new Number instance.
```php
$num = new Number(10);
$result = $num->add(5); // 15
```

##### `subtract(int|float|string|Text|Number $value): static`
Subtracts a value from the current number and returns a new Number instance.
```php
$num = new Number(10);
$result = $num->subtract(3); // 7
```

##### `multiply(int|float|string|Text|Number $factor): static`
Multiplies the current number by a factor and returns a new Number instance.
```php
$num = new Number(10);
$result = $num->multiply(2); // 20
```

##### `divide(int|float|string|Text|Number $factor): static`
Divides the current number by a factor and returns a new Number instance.
```php
$num = new Number(10);
$result = $num->divide(2); // 5
```

##### `round(int $precision = 0, RoundingMode $mode = RoundingMode::HalfAwayFromZero): static`
Rounds the number to the specified precision and returns a new Number instance.
```php
$num = new Number(3.14159);
$result = $num->round(2); // 3.14
```

##### `toDecimal(int $precision = 2): static`
Formats the number as a decimal string and returns a new Number instance.
```php
$num = new Number(1234.567);
$result = $num->toDecimal(2); // "1,234.57"
```

##### `toInteger(): static`
Converts the number to an integer and returns a new Number instance.
```php
$num = new Number(3.7);
$result = $num->toInteger(); // 4
```

##### `make(mixed $value): static`
Static factory method to create a new Number instance.
```php
$num = Number::make('123.45')->value // 123.45;
$num = Number::make(42)->value // 42;
```

### Text

The `Text` class extends Laravel's Stringable and provides additional text manipulation methods.

#### Constructor
```php
new Text(int|float|string|bool|Text|Number $value)
```

#### Methods

##### `make(mixed $value): static`
Static factory method to create a new Text instance.
```php
$text = Text::make(123); // "123"
$text = Text::make(true); // "1"
```

##### `of(string $string): static`
Creates a new Text instance from a string.
```php
$text = Text::of('Hello World');
```

##### `toInterval(string $delimiter = '...'): Interval`
Converts the text to an Interval by splitting on the delimiter.
```php
$text = new Text('2023-01-01...2023-12-31');
$interval = $text->toInterval(); // Interval with min and max dates

$text = new Text('1-100');
$interval = $text->toInterval('-'); // Interval with min=1, max=100
```

##### Stringable Methods
Since `Text` extends Laravel's `Stringable`, it inherits all Stringable methods.

### Sort

The `Sort` class represents a sort directive — a field name paired with a direction. It is a compound value object composed of a `Text` field and a `Direction` enum.

Its text representation uses prefix format: `-created_at` for descending, `created_at` for ascending.

#### Direction

`Direction` is a string-backed enum with two cases:

```php
Direction::Asc  // 'asc'
Direction::Desc // 'desc'
```

#### Methods

##### `make(string|Text|Sort $field, string|Direction|null $direction = null): static`
Static factory method to create a new Sort instance. Defaults to ascending if no direction is provided.
```php
$sort = Sort::make('created_at', Direction::Desc);
$sort = Sort::make('created_at', 'desc');
$sort = Sort::make('created_at'); // defaults to Direction::Asc
$sort = Sort::make(Text::make('created_at'), Direction::Asc);
```

If the field string starts with a `-` prefix, it is always interpreted as descending — even if an explicit direction is provided:
```php
$sort = Sort::make('-created_at');                    // field: created_at, direction: Desc
$sort = Sort::make('-created_at', Direction::Asc);    // field: created_at, direction: Desc
$sort = Sort::make('created_at');                     // field: created_at, direction: Asc
$sort = Sort::make('created_at', Direction::Desc);    // field: created_at, direction: Desc
```

Passing an existing `Sort` instance returns it as-is:
```php
$sort = Sort::make($existingSort); // returns $existingSort
```

##### `toText(): Text`
Returns the sort as a prefix-formatted `Text` instance.
```php
Sort::make('created_at', Direction::Desc)->toText()->toString(); // "-created_at"
Sort::make('created_at', Direction::Asc)->toText()->toString();  // "created_at"
```

##### `jsonSerialize(): string`
`Sort` implements `JsonSerializable`, serializing to its prefix format:
```php
json_encode(Sort::make('created_at', Direction::Desc));
// "-created_at"
```

#### Properties

```php
$sort = Sort::make('created_at', Direction::Desc);
$sort->field;     // Text instance
$sort->direction; // Direction::Desc
```

#### Castable

`Sort` implements `Castable` and stores as a prefix string in the database.

```php
protected $casts = [
    'sort' => Sort::class,
];
```

## Macros

All primitives use the `Illuminate\Support\Traits\Macroable` trait to allow for extension of methods.

```php
Number::macro('mod', function ($value) {
    return static::make($this->value % $value);
});

Number::make(5)->mod(2)->value // 1
```

## Castable

All primitives are castable.

```php
use Illuminate\Database\Eloquent\Model;
use Support\Primitives\Boolean;
use Support\Primitives\Sort;

class User extends Model
{
    protected $casts = [
        'is_admin' => Boolean::class,
        'default_sort' => Sort::class,
    ];
}
```
