<?php

declare(strict_types=1);

namespace TBuilder\Controllers;

final class HelloWorldController
{
    final public static function sayHi(): string
    {
        return 'Hello World' . PHP_EOL;
    }
}