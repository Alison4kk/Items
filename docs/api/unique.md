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

### Fluent API
```php
$collection->uniqued(string|callable $key): ItemsArray
$collection->unique(string|callable $key): ItemsArray
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

### Example 3: Fluent API
```php
$result = (new ItemsArray($users))
    ->filter(['active' => true])
    ->uniqued('email')
    ->get();
```

## Behavior

Keeps the first occurrence and removes subsequent duplicates.

## Performance

- Complexity: O(n)
- Uses array_unique internally (optimized)

## Next Steps

- [Filter →](/api/filter)
- [Group →](/api/group)
