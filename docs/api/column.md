---
title: Column - Extract Column from Items
description: Extract a single column value from each item in an array. Supports field names and custom callables.
head:
  - - meta
    - name: keywords
      content: "extract column, column extraction, array column, PHP column, extract values"
---

# Column - Extract Column Values

Extract a single column (field value) from each item, returning a flat array of values.

## Syntax

### Static API
```php
Items::column(array $items, string|callable $key): array
```

### Fluent API
```php
$collection->column(string|callable $key): array
```

## Examples

### Example 1: Extract by field name
```php
$users = [
    ['id' => 1, 'name' => 'Alice'],
    ['id' => 2, 'name' => 'Bob'],
    ['id' => 3, 'name' => 'Charlie'],
];

$names = Items::column($users, 'name');
// Result: ['Alice', 'Bob', 'Charlie']

$ids = Items::column($users, 'id');
// Result: [1, 2, 3]
```

### Example 2: Extract with callable
```php
$result = Items::column($users, fn($user) => strtoupper($user['name']));
// Result: ['ALICE', 'BOB', 'CHARLIE']
```

### Example 3: Fluent API
```php
$names = ItemBag::from($users)
    ->filter(['active' => true])
    ->column('name');
// Result: ['Alice', 'Charlie'] (only active users)
```

## Performance

- Complexity: O(n)
- Returns array indexed numerically (0, 1, 2, ...)
- Skips items where the field/callable returns null

## Next Steps

- [Index →](/api/index)
- [Group →](/api/group)
- [Transform →](/api/transform)
