<?php

require_once 'vendor/autoload.php';

use Support\Primitives\Text;

// Test 1: Basic Text::of() usage
echo "=== Test 1: Basic Text::of() usage ===\n";
$text1 = Text::of('Hello World');
echo 'Type: '.get_class($text1)."\n";
echo 'Value: '.$text1->value."\n";
echo 'String: '.(string) $text1."\n\n";

// Test 2: Fluent string operations
echo "=== Test 2: Fluent string operations ===\n";
$text2 = Text::of('  hello world  ')
    ->trim()
    ->title()
    ->append('!');

echo 'Type: '.get_class($text2)."\n";
echo 'Value: '.$text2->value."\n";
echo 'String: '.(string) $text2."\n\n";

// Test 3: Chaining multiple operations
echo "=== Test 3: Chaining multiple operations ===\n";
$text3 = Text::of('  UPPERCASE TEXT  ')
    ->trim()
    ->lower()
    ->replace('text', 'string')
    ->prepend('formatted: ');

echo 'Type: '.get_class($text3)."\n";
echo 'Value: '.$text3->value."\n";
echo 'String: '.(string) $text3."\n\n";

// Test 4: Using with other primitives
echo "=== Test 4: Using with other primatives ===\n";
$number = new \Support\Primitives\Number(42);
$text4 = Text::of($number)
    ->prepend('The answer is: ');

echo 'Type: '.get_class($text4)."\n";
echo 'Value: '.$text4->value."\n";
echo 'String: '.(string) $text4."\n\n";

echo "All tests completed successfully!\n";
