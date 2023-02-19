<?php

declare(strict_types=1);

if (php_sapi_name() !== 'cli') {
    echo '<h1>Only in CLI mode</h1>';
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

use TBuilder\Helpers\Env;
use TBuilder\Database\QueryBuilder;

Env::loadEnv();

$queryBuilder = new QueryBuilder();

$result = $queryBuilder->select('user', 'id, name, email');

foreach ($result as $person) {
    echo "\nId: {$person['id']}\nName: {$person['name']}\nE-mail: {$person['email']}\n";
}

$data = ['name' => 'Person example', 'email' => 'person.per@example.com'];
$queryBuilder->insert('user', $data);
