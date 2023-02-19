<p align="center">
  <a href="https://github.com/thiiagoms/tbuilder">
    <img src="assets/t.png" alt="Logo" width="80" height="80">
  </a>
     <h3 align="center">TBuilder - Database querybuilder :student:</h3>
</p>

Simple database querybuilder

- [Dependencies](#Dependencies)
- [Install](#Install)
- [Use](#Use)

### Dependencies
- +PHP8.2
- Composer

### Install :package:

01 - Install package with composer
```composer log
$ composer require thiiagoms/tbuilder
```

### Use
01 - Call `TBuilder\QueryBuilder` and pass your database credentials
```php
<?php

declare(strict_types=1);

if (php_sapi_name() !== 'cli') {
    echo '<h1>Only in CLI mode</h1>';
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

use TBuilder\Database\QueryBuilder;

$queryBuilder = new QueryBuilder('localhost', 3306, 'tbuilder', 'root', '');

// Select
$result = $queryBuilder->select('user', 'id, name, email');
foreach ($result as $person) {
    echo "\nId: {$person['id']}\nName: {$person['name']}\nE-mail: {$person['email']}\n";
}

// Insert
$payload = ['name' => 'Person example', 'email' => 'person.per@example.com'];
$id = $queryBuilder->insert('user', $payload);

echo "\nLast insert id {$id} \n";

// Update
$result = $queryBuilder->update('user', 'id = 1', ['name' => "TBuilder Test"]);
print_r($result); // true or false

// Delete
$result = $queryBuilder->delete('user', 'id = 1');
print_r($result); // true or false
```