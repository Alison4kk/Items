# Group - Group Items

Group items based on a field or custom logic.

## Syntax

### Static API
```php
Items::grouped(array $items, string|callable $key, ?string $subKey = null): array
```

### Fluent API
```php
$collection->grouped(string|callable $key, ?string $subKey = null): array
```

## Examples

### Example 1: Group by field
```php
$users = [
    ['id' => 1, 'name' => 'Alice', 'city' => 'São Paulo'],
    ['id' => 2, 'name' => 'Bob', 'city' => 'Rio'],
    ['id' => 3, 'name' => 'Charlie', 'city' => 'São Paulo'],
];

$grouped = Items::grouped($users, 'city');
// [
//   'São Paulo' => [
//     ['id' => 1, 'name' => 'Alice', ...],
//     ['id' => 3, 'name' => 'Charlie', ...],
//   ],
//   'Rio' => [
//     ['id' => 2, 'name' => 'Bob', ...],
//   ],
// ]
```

### Example 2: Group with callable
```php
$grouped = Items::grouped($users, fn($user) =>
    $user['age'] < 30 ? 'Young' : 'Experienced'
);
```

### Example 3: Fluent API
```php
$result = (new ItemsArray($users))
    ->filter(['active' => true])
    ->grouped('city')
    ->get();
```

## Performance

- Complexity: O(n)
- Preserves original item order

## Next Steps

- [Unique →](/api/unique)
- [Index →](/api/index)
