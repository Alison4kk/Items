# Items

Hybrid array/object manipulation library for **PHP 7.4+ and PHP 8** with:

- Static immutable operations
- Static in-place operations
- Fluent instance operations
- Dot notation for nested arrays/objects (`user.address.city`)

## Installation

```bash
composer require alison4kk/items
```

For local development:

```bash
composer install
```

## Quick start

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Items\Items;
use Items\ItemsArray;

$items = [
    ['id' => 1, 'user' => ['name' => 'Ana', 'active' => true]],
    ['id' => 2, 'user' => ['name' => 'Bob', 'active' => false]],
    ['id' => 3, 'user' => ['name' => 'Caio', 'active' => true]],
];

$activeOnly = Items::filtered($items, ['user.active', true]);

Items::filter($items, fn (array $item): bool => $item['id'] > 1);

$collection = new ItemsArray($activeOnly);
$ordered = $collection
    ->filtered(['user.name', 'CONTAINS', 'a'])
    ->sort(['user.name', 'ASC'])
    ->all();
```

## API

### Static immutable

- `Items::filtered(array $items, ...$conditions): array`
- `Items::sorted(array $items, ...$criteria): array`
- `Items::mapped(array $items, callable $mapper): array`

### Static in-place

- `Items::filter(array &$items, ...$conditions): void`
- `Items::sort(array &$items, ...$criteria): void`
- `Items::map(array &$items, callable $mapper): void`

### Fluent instance

- `$collection->filter(...$conditions): self` mutates current instance
- `$collection->filtered(...$conditions): self` returns a new instance
- `$collection->sort(...$criteria): self` mutates current instance
- `$collection->sorted(...$criteria): self` returns a new instance
- `$collection->map(callable $mapper): self` mutates current instance
- `$collection->mapped(callable $mapper): self` returns a new instance

### Dot notation

- `Items::getPath($target, string $path, $default = null)`
- `Items::setPath(&$target, string $path, $value): void`
- `$collection->get(string $path, $default = null)`
- `$collection->set(string $path, $value): self`
- `$collection->with(string $path, $value): self`

Works for nested arrays and objects, including mixed structures.

## Conditions format

You can pass conditions as closures or arrays:

```php
['field.path', 'value']                  // equality
['field.path', '>=', 10]                 // comparison
['field.path', 'IN', ['A', 'B']]         // membership
['field.path', 'CONTAINS', 'foo']        // string contains
fn (array $item): bool => $item['id'] > 10
```

## Testing

```bash
composer test
```

## PHP version

This package is compatible with `PHP 7.4+` and `PHP 8.x`, and uses PHPDoc generics (`@template`) instead of union/mixed type declarations.
