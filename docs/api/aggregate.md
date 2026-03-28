---
title: Aggregate - Count Sum Average Min Max Arrays
description: Calculate aggregate values from arrays. Count, sum, average, min, max operations on array items in PHP.
head:
  - - meta
    - name: keywords
      content: "count array items, sum array values, average array, min max array, aggregate functions, array statistics"
---

# Aggregate - Aggregation Operations

Operations that calculate values from all items.

## Syntax

### Count
```php
Items::count(array $items, $condition = null): int
$collection->count($condition = null): int
```

### Sum, Average, Min, Max
```php
Items::sum(array $items, string $field): float|int
Items::average(array $items, string $field): float|int
Items::max(array $items, string $field): float|int
Items::min(array $items, string $field): float|int

$collection->sum(string $field): float|int
$collection->average(string $field): float|int
$collection->max(string $field): float|int
$collection->min(string $field): float|int
```

### Count/Sum by group
```php
Items::countBy(array $items, string $field): array
Items::sumBy(array $items, string $groupField, string $sumField): array

$collection->countBy(string $field): array
$collection->sumBy(string $groupField, string $sumField): array
```

## Examples

### Example 1: Count
```php
$users = [
    ['id' => 1, 'active' => true],
    ['id' => 2, 'active' => false],
    ['id' => 3, 'active' => true],
];

$total = Items::count($users);
// 3

$active = Items::count($users, ['active' => true]);
// 2
```

### Example 2: Sum, Average, Min, Max
```php
$users = [
    ['id' => 1, 'age' => 28],
    ['id' => 2, 'age' => 35],
    ['id' => 3, 'age' => 42],
];

$total_age = Items::sum($users, 'age');
// 105

$avg_age = Items::average($users, 'age');
// 35

$youngest = Items::min($users, 'age');
// 28

$oldest = Items::max($users, 'age');
// 42
```

### Example 3: Count by group
```php
$countByCity = Items::countBy($users, 'city');
// ['São Paulo' => 2, 'Rio' => 1]
```

### Example 4: Fluent API
```php
$result = (new ItemsArray($users))
    ->filter(['active' => true])
    ->count();
// 2
```

## Performance

- Complexity: O(n)

## Next Steps

- [Check →](/api/check)
- [Transform →](/api/transform)
