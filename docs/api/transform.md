---
title: Transform - Transform Array Keys and Values
description: Transform array key-value structures in PHP. Map keys and create key-value pairs from array fields.
head:
  - - meta
    - name: keywords
      content: "transform arrays, key-value mapping, map keys, transform data structure, array transformation"
---

# Transform - Transform Keys and Values

Operations to transform the key-value structure of data.

## Syntax

### Key-Value Map
```php
Items::keyValue(array $items, string $keyField, string $valueField): array
$collection->keyValue(string $keyField, string $valueField): array
```

### Map Keys
```php
Items::mapKeys(array $items, callable $mapper): array
$collection->mapKeys(callable $mapper): array
```

## Examples

### Example 1: Key-Value Map
```php
$users = [
    ['id' => 1, 'name' => 'Alice'],
    ['id' => 2, 'name' => 'Bob'],
];

$map = Items::keyValue($users, 'id', 'name');
// [
//   1 => 'Alice',
//   2 => 'Bob',
// ]
```

### Example 2: Map Keys
```php
$data = [
    ['user_name' => 'Alice', 'user_age' => 28],
    ['user_name' => 'Bob', 'user_age' => 35],
];

$mapped = Items::mapKeys($data, fn($key) =>
    str_replace('user_', '', $key)
);
// names and ages without user_ prefix
```

### Example 3: Fluent API
```php
$result = (new ItemsArray($users))
    ->keyValue('id', 'name');
```

## Performance

- Complexity: O(n)

## Next Steps

- [Path - Dot Notation →](/api/path)
- [Aggregate →](/api/aggregate)
