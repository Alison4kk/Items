# Check - Verify Conditions

Test if items satisfy certain conditions.

## Syntax

### Static API
```php
Items::every(array $items, callable|array $condition): bool
Items::some(array $items, callable|array $condition): bool
Items::contains(array $items, array $search): bool
```

### Fluent API
```php
$collection->every(callable|array $condition): bool
$collection->some(callable|array $condition): bool
$collection->contains(array $search): bool
```

## Examples

### Example 1: Every - do all match?
```php
$users = [
    ['id' => 1, 'active' => true],
    ['id' => 2, 'active' => true],
];

$all_active = Items::every($users, ['active' => true]);
// true

$all_young = Items::every($users, ['age', '<', 30]);
// depends on data
```

### Example 2: Some - does any match?
```php
$has_active = Items::some($users, ['active' => true]);
// true if at least one is active
```

### Example 3: Contains - contains fields?
```php
$has_user = Items::contains($users, ['name' => 'Alice']);
// true if a user with name Alice exists
```

## Performance

- `every`: O(n) - stops on first failure
- `some`: O(n) - stops on first match
- `contains`: O(n)

## Next Steps

- [Find →](/api/find)
- [Aggregate →](/api/aggregate)
