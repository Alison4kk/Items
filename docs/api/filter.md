---
title: Filter - Filter Arrays by Conditions
description: Learn how to filter arrays of items by conditions in PHP. Use ItemsFilter with static or fluent APIs. Support for operators, callables, and dot notation.
head:
  - - meta
    - name: keywords
      content: "filter arrays, array filtering, PHP filter function, filter items, conditional filtering, array conditions"
---

# Filter - Filter Items

Find and return only items that match your conditions.

## Syntax

### Static Immutable API
```php
Items::filtered(array $items, ...$conditions): array
```

### Static In-Place API
```php
Items::filter(array &$items, ...$conditions): void
```

### Fluent API
```php
$collection->filtered(...$conditions): ItemsArray
$collection->filter(...$conditions): ItemsArray
```

## Supported Conditions

### Associative (equality)
```php
$result = Items::filtered($users, ['active' => true]);
$result = Items::filtered($users, ['city' => 'São Paulo', 'active' => true]);
// AND logic - all fields must match
```

### Indexed with operators
```php
// Comparison
Items::filtered($users, ['age', '>=', 18]);
Items::filtered($users, ['age', '>', 18]);
Items::filtered($users, ['age', '<=', 65]);
Items::filtered($users, ['age', '<', 65]);
Items::filtered($users, ['age', '=', 30]);
Items::filtered($users, ['age', '!=', 30]);

// Membership
Items::filtered($users, ['city', 'IN', ['São Paulo', 'Rio']]);
Items::filtered($users, ['city', 'NOT IN', ['Salvador']]);

// String operations
Items::filtered($users, ['email', 'CONTAINS', '.com']);
Items::filtered($users, ['name', 'STARTS_WITH', 'Al']);
Items::filtered($users, ['name', 'ENDS_WITH', 'ice']);
```

### Callable
```php
$result = Items::filtered($users, fn($user) => $user['age'] > 18);
$result = Items::filtered($users, fn($user) => strlen($user['email']) > 10);
```

## Examples

### Example 1: Filter by equality
```php
$users = [
    ['id' => 1, 'name' => 'Alice', 'city' => 'São Paulo', 'active' => true],
    ['id' => 2, 'name' => 'Bob', 'city' => 'Rio', 'active' => false],
    ['id' => 3, 'name' => 'Charlie', 'city' => 'São Paulo', 'active' => true],
];

$active = Items::filtered($users, ['active' => true]);
// [
//   ['id' => 1, 'name' => 'Alice', ...],
//   ['id' => 3, 'name' => 'Charlie', ...],
// ]
```

### Example 2: Multiple conditions (AND)
```php
$result = Items::filtered($users, ['city' => 'São Paulo', 'active' => true]);
// Only Alice and Charlie
```

### Example 3: Using operators
```php
$adults = Items::filtered($users, ['age', '>=', 18]);
$brazilians = Items::filtered($users, ['country', 'IN', ['BR', 'SP']]);
```

### Example 4: Using callable
```php
$result = Items::filtered($users, fn($user) =>
    $user['age'] > 25 && $user['active'] === true
);
```

### Example 5: Fluent API
```php
$result = (new ItemsArray($users))
    ->filter(['active' => true])
    ->filter(['city' => 'São Paulo'])
    ->get();
```

### Example 6: Dot notation with nested data
```php
$data = [
    ['user' => ['profile' => ['active' => true]]],
    ['user' => ['profile' => ['active' => false]]],
];

$active = Items::filtered($data, ['user.profile.active' => true]);
```

## Return Value

- **Static Immutable**: New array with filtered items
- **Static In-Place**: Void (modifies original array)
- **Fluent**: Returns new ItemsArray instance

## Performance

- Single iteration over items
- Complexity: O(n)
- No extra allocations in in-place version

## Next Steps

- [Sort →](/api/sort)
- [Map →](/api/map)
