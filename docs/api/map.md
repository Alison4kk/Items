---
title: Map - Transform Array Items in PHP
description: Transform arrays of items using map function in PHP. Apply callable to each array element. Static immutable or fluent API.
head:
  - - meta
    - name: keywords
      content: "map arrays, array transformation, PHP map, transform items, callback function, array manipulation"
---

# Map - Transform Items

Transform your data by applying a function to each item.

## Syntax

### Static Immutable API
```php
Items::mapped(array $items, callable $mapper): array
```

### Static In-Place API
```php
Items::map(array &$items, callable $mapper): void
```

### Fluent In-Place API
```php
$collection->map(callable $mapper): ItemBag
```

## Mapper Function Parameters

The mapper function receives two parameters:
```php
function($item, $key)
```

- `$item` - The current item
- `$key` - The item's key/index

## Examples

### Example 1: Extract specific fields
```php
$users = [
    ['id' => 1, 'name' => 'Alice', 'age' => 28],
    ['id' => 2, 'name' => 'Bob', 'age' => 35],
];

$names = Items::mapped($users, fn($user) => $user['name']);
// ['Alice', 'Bob']
```

### Example 2: Transform structure
```php
$transformed = Items::mapped($users, fn($user) => [
    'full_name' => $user['name'],
    'adult' => $user['age'] >= 18,
]);
// [
//   ['full_name' => 'Alice', 'adult' => true],
//   ['full_name' => 'Bob', 'adult' => true],
// ]
```

### Example 3: Use index
```php
$indexed = Items::mapped($users, fn($user, $key) => [
    'position' => $key + 1,
    'name' => $user['name'],
]);
// [
//   ['position' => 1, 'name' => 'Alice'],
//   ['position' => 2, 'name' => 'Bob'],
// ]
```

### Example 4: Fluent API (in-place)
```php
$result = ItemBag::from($users)
    ->filter(['age', '>=', 18])
    ->map(fn($user) => [
        'name' => strtoupper($user['name']),
        'age' => $user['age'],
    ])
    ->all();
```

### Example 5: Chaining with other operations
```php
$result = ItemBag::from($users)
    ->filter(['age', '>=', 18])
    ->sort('age', 'asc')
    ->map(fn($user) => [
        'name' => $user['name'],
        'age' => $user['age'],
    ])
    ->all();
```

### Example 6: Complex transformations
```php
$transformed = Items::mapped($users, fn($user) => [
    'name' => $user['name'],
    'age' => $user['age'],
    'category' => $user['age'] < 30 ? 'Young' : 'Experienced',
    'active_years' => 2025 - ($user['age'] - 18),
]);
```

## Use Cases

- Extract specific fields
- Transform data structure
- Rename fields
- Convert types
- Calculate derived values
- Format data for presentation

## Return Value

- **Static Immutable**: New array with transformed items
- **Static In-Place**: Void (modifies original array)
- **Fluent**: Returns self (ItemBag instance) for chaining

## Performance

- Single iteration over items
- Complexity: O(n)

## Next Steps

- [Filter →](/api/filter)
- [Sort →](/api/sort)
- [Unique →](/api/unique)
