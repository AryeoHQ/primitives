<?php

namespace Tests;

use Orchestra\Testbench;

abstract class TestCase extends Testbench\TestCase
{
    /** @var \Illuminate\Testing\TestResponse|null */
    public static $latestResponse = null;
}
