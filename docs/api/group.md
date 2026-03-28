---
title: Group - Group Arrays of Items by Field
description: Group arrays of items by field value or custom logic in PHP. Create array of arrays grouped by key. Static and fluent APIs supported.
head:
  - - meta
    - name: keywords
      content: "group arrays, grouping items, group by field, array grouping, PHP grouping, organize array data"
---

# Group - Group Items

Group items based on a field or custom logic.

## Syntax

### Static API
```php
// Immutable - returns new grouped array
Items::grouped(array $items, string|callable $key, ?string $subKey = null): array

// In-place - modifies the array
Items::group(array &$items, string|callable $key, ?string $subKey = null): void
```

### Fluent API
```php
// Immutable - returns array
$collection->grouped(string|callable $key, ?string $subKey = null): array

// In-place - modifies and returns self
$collection->group(string|callable $key, ?string $subKey = null): ItemBag
```

## Examples

### Example 1: Group by field
```php
$users = [
    ['id' => 1, 'name' => 'Alice', 'city' => 'São Paulo'],
    ['id' => 2, 'name' => 'Bob', 'city' => 'Rio'],
    ['id' => 3, 'name' => 'Charlie', 'city' => 'São Paulo'],
];

$grouped = Items::grouped($users, 'city');
// [
//   'São Paulo' => [
//     ['id' => 1, 'name' => 'Alice', ...],
//     ['id' => 3, 'name' => 'Charlie', ...],
//   ],
//   'Rio' => [
//     ['id' => 2, 'name' => 'Bob', ...],
//   ],
// ]
```

### Example 2: Group with callable
```php
$grouped = Items::grouped($users, fn($user) =>
    $user['age'] < 30 ? 'Young' : 'Experienced'
);
```

### Example 3: Fluent API (in-place)
```php
$result = ItemBag::from($users)
    ->filter(['active' => true])
    ->group('city')
    ->all();
```

## Performance

- Complexity: O(n)
- Preserves original item order

## Next Steps

- [Unique →](/api/unique)
- [Index →](/api/index)
