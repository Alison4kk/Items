---
title: Unique - Remove Duplicates from Arrays
description: Remove duplicate items from arrays in PHP. Deduplicate by field value or custom logic.
head:
  - - meta
    - name: keywords
      content: "remove duplicates, deduplicate array, unique items, array deduplication, remove duplicate records"
---

# Unique - Remove Duplicates

Remove duplicate items based on a field or custom logic.

## Syntax

### Static Immutable API
```php
Items::uniqued(array $items, string|callable $key): array
```

### Static In-Place API
```php
Items::unique(array &$items, string|callable $key): void
```

### Fluent In-Place API
```php
$collection->unique(string|callable $key): ItemBag
```

## Examples

### Example 1: Unique by field
```php
$users = [
    ['id' => 1, 'email' => 'alice@example.com'],
    ['id' => 2, 'email' => 'bob@example.com'],
    ['id' => 3, 'email' => 'alice@example.com'], // duplicate
];

$unique = Items::uniqued($users, 'email');
// Returns only the first 2
```

### Example 2: Unique with callable
```php
$unique = Items::uniqued($users, fn($user) => strtolower($user['email']));
// Uses the function to generate the uniqueness key
```

### Example 3: Fluent API (in-place)
```php
$result = ItemBag::from($users)
    ->filter(['active' => true])
    ->unique('email')
    ->all();
```

## Behavior

Keeps the first occurrence and removes subsequent duplicates.

## Performance

- Complexity: O(n)
- Uses array_unique internally (optimized)

## Next Steps

- [Filter →](/api/filter)
- [Group →](/api/group)
