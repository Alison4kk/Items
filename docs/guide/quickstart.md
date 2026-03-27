# Quick Start

## Basic Example

```php
<?php
require 'vendor/autoload.php';

use Items\Items;
use Items\ItemsArray;

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

Elegant chaining of operations:

```php
$result = (new ItemsArray($users))
    ->filter(['active' => true])
    ->sorted('age', 'asc')
    ->mapped(fn($u) => ['name' => $u['name'], 'age' => $u['age']])
    ->get();

// Result:
// [
//   ['name' => 'Alice', 'age' => 28],
//   ['name' => 'Charlie', 'age' => 42],
// ]
```

## Common Operations

### Filter (find)
```php
// Static
$active = Items::filtered($users, ['active' => true]);

// Fluent
$active = (new ItemsArray($users))->filter(['active' => true])->get();
```

### Sort
```php
// Static
$sorted = Items::sorted($users, 'age', 'desc');

// Fluent
$sorted = (new ItemsArray($users))->sorted('age', 'desc')->get();
```

### Group
```php
// Static
$grouped = Items::grouped($users, 'city');
// ['São Paulo' => [...], 'Rio' => [...]]

// Fluent
$grouped = (new ItemsArray($users))->grouped('city')->get();
```

### Count
```php
// Static
$count = Items::count($users);

// With condition
$activeCount = Items::count($users, ['active' => true]);

// Fluent
$count = (new ItemsArray($users))->filter(['active' => true])->count();
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
