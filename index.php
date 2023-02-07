<?php

declare(strict_types=1);

if (php_sapi_name() !== 'cli') {
    echo '<h1>Only in CLI mode</h1>';
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

use TBuilder\Helpers\Env;

Env::loadEnv();

echo $_ENV['DATABASE_HOST'] . PHP_EOL;