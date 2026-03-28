---
title: Concepts - Three APIs for Array Manipulation
description: Learn the three different API styles for manipulating arrays with Items. Static immutable, in-place, and fluent interfaces explained.
head:
  - - meta
    - name: keywords
      content: "array manipulation concepts, fluent interface, static API, immutable arrays, in-place modification, PHP arrays"
---

# Concepts - Array Manipulation APIs

## Three APIs, One Logic

Items offers three different ways to work with data, but they all implement the same logic:

### 1. Static Immutable API

```php
Items::filtered(...) // Returns new array
Items::sorted(...)
Items::mapped(...)
```

**Characteristics:**
- Does not modify original data
- Returns new arrays
- Ideal for functional programming
- Better for avoiding side effects

**When to use:**
- When you need to keep the original data
- In operations that need to be reversible
- When working with functional programming

### 2. Static In-Place API

```php
Items::filter(...)   // Modifies the passed array
Items::sort(...)
Items::map(...)
```

**Characteristics:**
- Modifies the array/object directly
- Does not return new data
- More memory efficient
- Ideal when performance is critical

**When to use:**
- When you are sure you can modify the data
- In applications with large data volumes
- When you want to avoid memory duplication

### 3. Fluent API

```php
// Using constructor
(new ItemBag($data))
    ->filter(...)
    ->sort(...)
    ->map(...)
    ->all();

// Or using the static from() method
ItemBag::from($data)
    ->filter(...)
    ->sort(...)
    ->map(...)
    ->all();
```

**Characteristics:**
- Modern and readable interface
- Method chaining
- In-place modifications (modifies internal state)
- Returns `$this` for fluent chaining
- Perfect for elegant, readable code

**When to use:**
- When you want readable and expressive code
- In modern applications that value clarity
- When working with multiple operations in sequence

## Conditions

The libraries support multiple condition formats:

### Associative Array (Logical AND)
```php
Items::filtered($users, ['active' => true, 'city' => 'São Paulo']);
// Returns only active users IN São Paulo
```

### Indexed Array with Operators
```php
Items::filtered($users, ['age', '>=', 18]);
Items::filtered($users, ['city', 'IN', ['São Paulo', 'Rio']]);
Items::filtered($users, ['email', 'CONTAINS', '.com']);
```

### Callable (function)
```php
Items::filtered($users, fn($user) => $user['age'] > 18 && $user['active']);
```

## Dot Notation

Access nested data with dot notation:

```php
$data = [
    'user' => [
        'name' => 'Alice',
        'profile' => [
            'address' => [
                'city' => 'São Paulo'
            ]
        ]
    ]
];

// Get value
$city = Items::getPath($data, 'user.profile.address.city');
// 'São Paulo'

// Set value
Items::setPath($data, 'user.profile.address.city', 'Rio');
```

## Aggregation Operations

Operations that return a single value:

### Count - count items
```php
$total = Items::count($users);
$active = Items::count($users, ['active' => true]);
```

### Sum - sum values
```php
$totalAge = Items::sum($users, 'age');
```

### Average - average
```php
$avgAge = Items::average($users, 'age');
```

### Min/Max
```php
$youngest = Items::min($users, 'age');
$oldest = Items::max($users, 'age');
```

### Count/Sum by group
```php
$countByCity = Items::countBy($users, 'city');
// ['São Paulo' => 2, 'Rio' => 1, ...]

$sumByCity = Items::sumBy($users, 'city', 'age');
// ['São Paulo' => 70, 'Rio' => 35, ...]
```

## Supported Data Types

### Arrays
```php
$array = ['id' => 1, 'name' => 'Alice'];
Items::filtered($array, ['id' => 1]);
```

### Objects (stdClass)
```php
$obj = (object) ['id' => 1, 'name' => 'Alice'];
Items::filtered([$obj], ['id' => 1]);
```

### Custom Objects
```php
class User {
    public int $id;
    public string $name;
}

$user = new User();
$user->id = 1;
$user->name = 'Alice';

Items::filtered([$user], ['id' => 1]);
```

### Mixed Structures
Items works with any combination of these types in nested structures.

## Performance

### Choose the Right API

**Static Immutable:**
- Better for small to medium data
- Safe and predictable
- More memory allocation

**Static In-Place:**
- Better for large data
- More memory efficient
- Requires care with references

**Fluent:**
- Similar performance to immutable
- Much more readable code
- Great cost-benefit

## Next Steps

- [API Reference - Filter →](/api/filter)
- [API Reference - Sort →](/api/sort)
- [All operations →](/api/)
