---
title: Installation - Items PHP Library
description: Install Items with Composer. Quick setup for PHP 7.4+ array manipulation library with zero dependencies.
head:
  - - meta
    - name: keywords
      content: "install items, PHP package, composer, array library installation"
---

# Installation

## Via Composer (Recommended)

```bash
composer require alison4kk/items
```

## Requirements

- **PHP 7.4** or higher
- **PHP 8.x** fully supported
- Composer

## Verify Installation

After installing, verify that everything is working:

```php
<?php
require 'vendor/autoload.php';

use Items\Items;
use Items\ItemBag;

// Test with static API
$data = [['id' => 1, 'name' => 'Alice']];
$result = Items::mapped($data, fn($item) => $item['name']);

// Test with fluent API
$result = (new ItemBag($data))->map(fn($item) => $item['name'])->all();

echo "Items installed successfully!";
?>
```

## Next Steps

- [Quick Start →](/guide/quickstart)
- [Concepts →](/guide/concepts)
- [API Reference →](/api/filter)
