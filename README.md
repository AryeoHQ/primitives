# Primatives
A package providing object-oriented wrappers for primitive types, allowing you to encapsulate values and perform transformations on them.

## Installation
```bash
composer require aryeo/primatives
```

## Usage

### Boolean

The `Boolean` class provides an object-oriented wrapper for boolean values with type-safe operations.

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
$bool = Bollean::make('yes') // true
$bool = Bollean::make('no') // false
$bool = Bollean::make('on') // true
$bool = Bollean::make('off') // false

##### `inverse()`
Returns a new Boolean instance with the inverse value.
```php
$bool = Boolean::make('true');
$inverted = $bool->inverse(); // false
```

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
new Text(int|float|string|Text|Number|Boolean $value)
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

## Macros

All primitives use the `Illuminate\Support\Traits\Macroable` trait to allow for extension of methods.

```php
Number::macro('mod', function ($value) {
    return static::make($this->value % $value);
});

Number::make(5)->mod(2)->value // 1
```

## Castable

All primatives are castable

```php
// Example: Using a primitive as a cast in an Eloquent model

use Illuminate\Database\Eloquent\Model;
use Support\Primitives\Boolean;

class User extends Model
{
    protected $casts = [
        'is_admin' => Boolean::class,
    ];
}

// Usage:
$user = new User(['is_admin' => true]);
$user->is_admin; // Instance of Boolean
```

