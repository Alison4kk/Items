---
title: API Reference - Complete Array Operations
description: Full API documentation for Items library. Filter, sort, map, group, count, and transform arrays with code examples.
head:
  - - meta
    - name: keywords
      content: "API documentation, array operations, PHP functions reference, filter sort map group"
---

# Index - Create Indexed Map

Create an associative array indexed by a field or function result.

## Syntax

### Static API
```php
Items::indexed(array $items, string|callable $key): array
```

### Fluent API
```php
$collection->indexed(string|callable $key): array
```

## Examples

### Example 1: Index by field
```php
$users = [
    ['id' => 1, 'name' => 'Alice'],
    ['id' => 2, 'name' => 'Bob'],
    ['id' => 3, 'name' => 'Charlie'],
];

$indexed = Items::indexed($users, 'id');
// [
//   1 => ['id' => 1, 'name' => 'Alice'],
//   2 => ['id' => 2, 'name' => 'Bob'],
//   3 => ['id' => 3, 'name' => 'Charlie'],
// ]
```

### Example 2: Index with callable
```php
$indexed = Items::indexed($users, fn($user) => $user['name']);
// [
//   'Alice' => [...],
//   'Bob' => [...],
//   'Charlie' => [...],
// ]
```

### Example 3: Fluent API
```php
$result = (new ItemsArray($users))
    ->filter(['active' => true])
    ->indexed('id');
```

## Performance

- Complexity: O(n)
- Fast access to items by key

## Next Steps

- [Group →](/api/group)
- [Find →](/api/find)
