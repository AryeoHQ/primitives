<?php

require_once 'vendor/autoload.php';

use Support\Primitives\Text;

// Test the proxy functionality
echo "Testing Text class proxy to Str::of():\n\n";

// Test 1: Basic string manipulation
$result1 = Text::upper('hello world');
echo "Text::upper('hello world'): ".$result1."\n";

// Test 2: Chaining methods
$result2 = Text::of('hello world')->upper()->snake();
echo "Text::of('hello world')->upper()->snake(): ".$result2."\n";

// Test 3: Another method
$result3 = Text::camel('hello_world');
echo "Text::camel('hello_world'): ".$result3."\n";

// Test 4: Method with multiple parameters
$result4 = Text::limit('This is a very long string that should be limited', 20);
echo "Text::limit('This is a very long string...', 20): ".$result4."\n";

echo "\nAll tests completed!\n";
