---
title: Path - Dot Notation for Nested Array Access
description: Access and modify nested array data using dot notation in PHP. Get and set values in deeply nested structures easily.
head:
  - - meta
    - name: keywords
      content: "dot notation, nested arrays, nested objects, access nested data, array paths, nested data structures"
---

# Path - Dot Notation for Nested Data

Access and modify nested data using dot notation.

## Syntax

### Static API
```php
Items::getPath($item, string $path, $default = null): mixed
Items::setPath(&$item, string $path, $value): void
```

### Fluent API
```php
$collection->get(string $path, $default = null): mixed
$collection->set(string $path, $value): ItemBag
```

## What is Dot Notation?

Dot notation allows you to access nested data without multiple access levels:

```php
// Without dot notation
$city = $users[0]['address']['city'];

// With dot notation
$city = Items::getPath($users[0], 'address.city');
```

## Examples

### Example 1: Get nested value
```php
$user = [
    'name' => 'Alice',
    'address' => [
        'city' => 'São Paulo',
        'zip' => '01310-100'
    ]
];

$city = Items::getPath($user, 'address.city');
// 'São Paulo'

$zip = Items::getPath($user, 'address.zip');
// '01310-100'

// With default
$state = Items::getPath($user, 'address.state', 'SP');
// 'SP'
```

### Example 2: Set nested value
```php
Items::setPath($user, 'address.city', 'Rio');
// $user['address']['city'] is now 'Rio'

Items::setPath($user, 'address.country', 'Brazil');
// Creates new value if doesn't exist
```

### Example 3: With arrays
```php
$data = [
    ['user' => ['name' => 'Alice', 'age' => 28]],
    ['user' => ['name' => 'Bob', 'age' => 35]],
];

Items::setPath($data[0], 'user.age', 29);
// Modifies nested age
```

### Example 4: Filter with dot notation
```php
$result = Items::filtered($data, ['user.age' => 28]);
// Filters using nested field
```

### Example 5: Fluent API
```php
$value = ItemBag::from($data)
    ->get('0.user.name');
// 'Alice'

$updated = ItemBag::from($data)
    ->set('0.user.city', 'Rio');
// Sets in-place
```

## Limitations

- Works with arrays and public object properties
- Creates path if not exists (setPath)
- Use default value to avoid errors (getPath)

## Performance

- Complexity: O(depth) for each access

## Next Steps

- [All operations →](/api/)
- [Concepts →](/guide/concepts)
