---
title: Quick Start - Array Manipulation Examples
description: Get started with Items PHP library. Learn by example how to filter, sort, map and manipulate arrays of items with different API styles.
head:
  - - meta
    - name: keywords
      content: "filter arrays, sort arrays, map arrays, PHP examples, array manipulation examples, fluent API, static API"
---

# Quick Start

## Basic Example

```php
<?php
require 'vendor/autoload.php';

use Items\Items;
use Items\ItemBag;

$users = [
    ['id' => 1, 'name' => 'Alice', 'city' => 'São Paulo', 'age' => 28, 'active' => true],
    ['id' => 2, 'name' => 'Bob', 'city' => 'Rio', 'age' => 35, 'active' => false],
    ['id' => 3, 'name' => 'Charlie', 'city' => 'São Paulo', 'age' => 42, 'active' => true],
];
```

## Static Immutable API

Does not modify original data, returns a new array:

```php
// Filter
$active = Items::filtered($users, ['active' => true]);

// Sort
$sorted = Items::sorted($active, 'age', 'asc');

// Map
$names = Items::mapped($sorted, fn($u) => $u['name']);
// ['Alice', 'Charlie']
```

## Static In-Place API

Modifies the array directly:

```php
Items::filter($users, ['active' => true]);
// $users now contains only active users

Items::sort($users, 'age', 'asc');
// $users is sorted by age

Items::map($users, fn($u) => ['name' => $u['name'], 'age' => $u['age']]);
// $users contains only name and age
```

## Fluent API

Elegant chaining of in-place operations:

```php
// There are two ways to create a new ItemBag:
$result = (new ItemBag($users))
    ->filter(['active' => true])
    ->sort('age', 'asc')
    ->map(fn($u) => ['name' => $u['name'], 'age' => $u['age']])
    ->all();

// Or using the static from() method:
$result = ItemBag::from($users)
    ->filter(['active' => true])
    ->sort('age', 'asc')
    ->map(fn($u) => ['name' => $u['name'], 'age' => $u['age']])
    ->all();

// Result:
// [
//   ['name' => 'Alice', 'age' => 28],
//   ['name' => 'Charlie', 'age' => 42],
//   ['name' => 'Diana', 'age' => 31],
// ]
```

## Common Operations

### Filter (find)
```php
// Static
$active = Items::filtered($users, ['active' => true]);

// Fluent
$active = (new ItemBag($users))->filter(['active' => true])->all();
```

### Sort
```php
// Static
$sorted = Items::sorted($users, 'age', 'desc');

// Fluent
$sorted = (new ItemBag($users))->sort('age', 'desc')->all();
```

### Group
```php
// Static - returns new array, doesn't modify
$grouped = Items::grouped($users, 'city');
// ['São Paulo' => [...], 'Rio' => [...]]

// Fluent - modifies in-place and returns self
$grouped = ItemBag::from($users)->group('city')->all();
```

### Index
```php
// Static - returns new indexed array
$indexed = Items::indexed($users, 'id');
// [1 => [...], 2 => [...], ...]

// Fluent - indexes in-place
$indexed = ItemBag::from($users)->index('id')->all();
```

### Column
```php
// Static
$names = Items::column($users, 'name');
// ['Alice', 'Bob', 'Charlie']

// Fluent
$names = (new ItemBag($users))->column('name');
```

### Count
```php
// Static
$count = Items::count($users);

// With condition
$activeCount = Items::count($users, ['active' => true]);

// Fluent
$count = (new ItemBag($users))->filter(['active' => true])->count();
```

### Dot notation for nested data
```php
$data = [
    ['user' => ['name' => 'Alice', 'address' => ['city' => 'São Paulo']]],
    ['user' => ['name' => 'Bob', 'address' => ['city' => 'Rio']]],
];

// Access nested data
$city = Items::getPath($data[0], 'user.address.city');
// 'São Paulo'

// Modify nested data
Items::setPath($data[0], 'user.address.city', 'Brasília');
```

## Next Steps

- [Concepts →](/guide/concepts)
- [API Reference →](/api/filter)
