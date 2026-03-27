# Find - Find Items

Find the first or last item that matches a condition.

## Syntax

### Static API
```php
Items::first(array $items, $condition = null): mixed
Items::last(array $items, $condition = null): mixed
```

### Fluent API
```php
$collection->first($condition = null): mixed
$collection->last($condition = null): mixed
```

## Examples

### Example 1: Find first item
```php
$users = [
    ['id' => 1, 'name' => 'Alice'],
    ['id' => 2, 'name' => 'Bob'],
];

$first = Items::first($users);
// ['id' => 1, 'name' => 'Alice']
```

### Example 2: Find first with condition
```php
$first_adult = Items::first($users, ['age', '>=', 18]);
```

### Example 3: Find last
```php
$last = Items::last($users);
$last_active = Items::last($users, ['active' => true]);
```

## Performance

- Complexity: O(n) worst case
- For first item without condition: O(1)

## Next Steps

- [Check →](/api/check)
- [Aggregate →](/api/aggregate)
