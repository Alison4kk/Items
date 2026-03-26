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

$users = [
    ['id' => 1, 'name' => 'Alice', 'city' => 'São Paulo', 'age' => 28, 'active' => true],
    ['id' => 2, 'name' => 'Bob', 'city' => 'Rio', 'age' => 35, 'active' => false],
    ['id' => 3, 'name' => 'Charlie', 'city' => 'São Paulo', 'age' => 42, 'active' => true],
];

// Static API - immutable
$active = Items::filtered($users, ['active' => true]);

// Static API - in-place
Items::filter($users, ['city' => 'São Paulo']);

// Fluent API - with chaining
$result = (new ItemsArray($users))
    ->filter(['active' => true])
    ->sorted('age', 'asc')
    ->mapped(fn($u) => ['name' => $u['name'], 'age' => $u['age']]);
```

## API Reference

### Filter (search, find matching items)

**Static immutable:**
```php
Items::filtered(array $items, ...$conditions): array
```

**Static in-place:**
```php
Items::filter(array &$items, ...$conditions): void
```

**Fluent immutable:**
```php
$collection->filtered(...$conditions): ItemsArray
```

**Fluent in-place:**
```php
$collection->filter(...$conditions): ItemsArray
```

Returns array or instance with only items matching all conditions.

### Sort (order items)

**Static immutable:**
```php
Items::sorted(array $items, string $field, string $direction = 'ASC'): array
Items::sorted(array $items, callable $comparator): array
```

**Static in-place:**
```php
Items::sort(array &$items, string $field, string $direction = 'ASC'): void
Items::sort(array &$items, callable $comparator): void
```

**Fluent versions:**
```php
$collection->sorted(string $field, string $direction = 'ASC'): ItemsArray
$collection->sort(string $field, string $direction = 'ASC'): ItemsArray
$collection->sorted(callable $comparator): ItemsArray
$collection->sort(callable $comparator): ItemsArray
```

Supports 'ASC' and 'DESC' directions. Works with dot notation paths.

### Map (transform items)

**Static immutable:**
```php
Items::mapped(array $items, callable $mapper): array
```

**Static in-place:**
```php
Items::map(array &$items, callable $mapper): void
```

**Fluent versions:**
```php
$collection->mapped(callable $mapper): ItemsArray
$collection->map(callable $mapper): ItemsArray
```

Mapper receives `($item, $key)` and returns transformed value.

### Unique (remove duplicates)

**Static immutable:**
```php
Items::uniqued(array $items, string|callable $key): array
```

**Static in-place:**
```php
Items::unique(array &$items, string|callable $key): void
```

**Fluent versions:**
```php
$collection->uniqued(string|callable $key): ItemsArray
$collection->unique(string|callable $key): ItemsArray
```

Removes duplicate items based on field or callable key.

### Group (organize by field)

```php
Items::grouped(array $items, string|callable $key, ?string $subKey = null): array
$collection->grouped(string|callable $key, ?string $subKey = null): array
```

Returns array of grouped items keyed by field values.

### Index (create indexed map)

```php
Items::indexed(array $items, string|callable $key): array
$collection->indexed(string|callable $key): array
```

Returns associative array keyed by field or callable result.

### Find (get first/last matching)

```php
Items::first(array $items, $condition = null): mixed
Items::last(array $items, $condition = null): mixed
$collection->first($condition = null): mixed
$collection->last($condition = null): mixed
```

Returns first/last item optionally matching condition.

### Check (test conditions)

```php
Items::every(array $items, callable|array $condition): bool
Items::some(array $items, callable|array $condition): bool
Items::contains(array $items, array $search): bool
$collection->every(callable|array $condition): bool
$collection->some(callable|array $condition): bool
$collection->contains(array $search): bool
```

Test if all/any items match, or if any item contains all search fields.

### Count & Sum

```php
Items::count(array $items, $condition = null): int
Items::sum(array $items, string $field): float|int
Items::average(array $items, string $field): float|int
Items::max(array $items, string $field): float|int
Items::min(array $items, string $field): float|int
$collection->count($condition = null): int
$collection->sum(string $field): float|int
$collection->average(string $field): float|int
$collection->max(string $field): float|int
$collection->min(string $field): float|int
```

Aggregate operations. Count optionally filters by condition.

### Group Aggregates

```php
Items::countBy(array $items, string $field): array
Items::sumBy(array $items, string $groupField, string $sumField): array
$collection->countBy(string $field): array
$collection->sumBy(string $groupField, string $sumField): array
```

Count/sum items grouped by field.

### Transform Keys/Values

```php
Items::keyValue(array $items, string $keyField, string $valueField): array
Items::mapKeys(array $items, callable $mapper): array
$collection->keyValue(string $keyField, string $valueField): array
$collection->mapKeys(callable $mapper): array
```

Create key-value maps or transform keys using callable.

### Dot Notation (nested access)

**Static:**
```php
Items::getPath($item, string $path, $default = null): mixed
Items::setPath(&$item, string $path, $value): void
```

**Fluent:**
```php
$collection->get(string $path, $default = null): mixed
$collection->set(string $path, $value): ItemsArray
$collection->with(string $path, $value): ItemsArray
```

Access nested arrays and objects using dot notation:
```php
Items::getPath($user, 'profile.address.city');
$collection->set('0.user.settings.theme', 'dark');
```

## Conditions Format

Pass conditions as associative arrays (all must match):

```php
['field' => 'value']                           // equality
['active' => true, 'city' => 'São Paulo']     // AND logic
```

Or as indexed arrays with operators:

```php
['field', 'value']                             // equality
['field', '>=', 10]                            // comparison
['field', 'IN', ['A', 'B']]                    // membership
['field', 'CONTAINS', 'substring']             // contains
fn (array $item): bool => $item['id'] > 10    // callable
```

## Data Types

Works with arrays and objects (using stdClass or custom classes). All methods support:

- Nested arrays and objects
- Mixed structures
- Dot notation paths like `user.profile.address.zip`

## Testing

```bash
composer test
```

All 46 tests validate filter, sort, map, unique, group, index, find, count, sum, average, min, max, grouping aggregates, key-value operations, and dot notation functionality.

## PHP Version

Requires PHP 7.4+ with full PHP 8.x support.

## License

MIT


This package is compatible with `PHP 7.4+` and `PHP 8.x`, and uses PHPDoc generics (`@template`) instead of union/mixed type declarations.
