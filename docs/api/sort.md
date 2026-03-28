---
title: Sort - Sort Arrays by Field or Logic
description: Sort arrays of items in PHP by field value or custom comparator. Support for ascending/descending order with static or fluent APIs.
head:
  - - meta
    - name: keywords
      content: "sort arrays, array sorting, PHP sort, sort by field, custom sorting, ascending descending"
---

# Sort - Sort Items

Sort your data by field or custom logic.

## Syntax

### Static Immutable API
```php
Items::sorted(array $items, string $field, string $direction = 'ASC'): array
Items::sorted(array $items, callable $comparator): array
```

### Static In-Place API
```php
Items::sort(array &$items, string $field, string $direction = 'ASC'): void
Items::sort(array &$items, callable $comparator): void
```

### Fluent API
```php
$collection->sorted(string $field, string $direction = 'ASC'): ItemsArray
$collection->sorted(callable $comparator): ItemsArray
$collection->sort(string $field, string $direction = 'ASC'): ItemsArray
$collection->sort(callable $comparator): ItemsArray
```

## Supported Directions

- `'ASC'` or `'asc'` - Ascending (default)
- `'DESC'` or `'desc'` - Descending

## Examples

### Example 1: Sort by field (ascending)
```php
$users = [
    ['id' => 3, 'name' => 'Charlie', 'age' => 42],
    ['id' => 1, 'name' => 'Alice', 'age' => 28],
    ['id' => 2, 'name' => 'Bob', 'age' => 35],
];

$sorted = Items::sorted($users, 'age', 'asc');
// Sorted by age ascending
// Alice (28), Bob (35), Charlie (42)
```

### Example 2: Sort descending
```php
$sorted = Items::sorted($users, 'age', 'desc');
// Charlie (42), Bob (35), Alice (28)
```

### Example 3: Custom sorting with callable
```php
$sorted = Items::sorted($users, fn($a, $b) =>
    strlen($a['name']) <=> strlen($b['name'])
);
// Sorts by name length
```

### Example 4: Fluent API
```php
$result = (new ItemsArray($users))
    ->filter(['age', '>=', 18])
    ->sorted('age', 'asc')
    ->get();
```

### Example 5: Dot notation with nested data
```php
$data = [
    ['user' => ['profile' => ['age' => 42]]],
    ['user' => ['profile' => ['age' => 28]]],
];

$sorted = Items::sorted($data, 'user.profile.age', 'asc');
```

### Example 6: Multiple sorts
```php
// First by city, then by age (chain)
$result = (new ItemsArray($users))
    ->sorted('city', 'asc')
    ->sorted('age', 'asc')
    ->get();
```

## Data Types

Works with:
- Numbers (int, float)
- Strings
- Dates (if comparable)
- Booleans

## Return Value

- **Static Immutable**: New sorted array
- **Static In-Place**: Void (modifies original array)
- **Fluent**: Returns new ItemsArray instance

## Performance

- Complexity: O(n log n)
- Uses PHP's native sort algorithm (introsort)

## Next Steps

- [Filter →](/api/filter)
- [Map →](/api/map)
