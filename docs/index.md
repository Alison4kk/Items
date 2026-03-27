---
layout: home

hero:
  name: "Items"
  text: "Hybrid PHP library for array and object manipulation"
  tagline: "Same methods. Multiple ways. Choose yours."
  image:
    src: /logo.svg
    alt: Items
  actions:
    - theme: brand
      text: "Get Started"
      link: /guide/
    - theme: alt
      text: "View on GitHub"
      link: https://github.com/alison4kk/items

features:
  - icon: "⚡"
    title: "Modern"
    details: "Support for PHP 7.4+ and PHP 8.x with generics via PHPDoc"

  - icon: "🔄"
    title: "Hybrid"
    details: "Immutable static API, in-place static API, fluent interface with chaining"

  - icon: "🎯"
    title: "Flexible"
    details: "Works with arrays, objects and mixed structures. Dot notation for nested access"

  - icon: "🚀"
    title: "Complete"
    details: "Filter, sort, map, unique, group, index, find, aggregate and much more"

---

## The Difference

Choose the style that makes the most sense for your code:

### Static API (Immutable)
```php
$active = Items::filtered($users, ['active' => true]);
// Original is not modified
```

### Static API (In-Place)
```php
Items::filter($users, ['active' => true]);
// Modifies $users directly
```

### Fluent API (Chainable)
```php
$result = (new ItemsArray($users))
    ->filter(['active' => true])
    ->sorted('age', 'asc')
    ->mapped(fn($u) => ['name' => $u['name']]);
```

## Getting Started

### Installation
```bash
composer require alison4kk/items
```

### Basic Usage
```php
use Items\Items;
use Items\ItemsArray;

$users = [
    ['id' => 1, 'name' => 'Alice', 'city' => 'São Paulo', 'age' => 28, 'active' => true],
    ['id' => 2, 'name' => 'Bob', 'city' => 'Rio', 'age' => 35, 'active' => false],
];

// Choose your style!
$filtered = Items::filtered($users, ['active' => true]);
// or
(new ItemsArray($users))->filter(['active' => true])->get();
```

Read the [complete documentation](/guide/) to explore all features.
