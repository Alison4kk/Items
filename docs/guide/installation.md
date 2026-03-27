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
use Items\ItemsArray;

// Test with static API
$data = [['id' => 1, 'name' => 'Alice']];
$result = Items::mapped($data, fn($item) => $item['name']);

// Test with fluent API
$result = (new ItemsArray($data))->mapped(fn($item) => $item['name'])->get();

echo "Items installed successfully!";
?>
```

## Next Steps

- [Quick Start →](/guide/quickstart)
- [Concepts →](/guide/concepts)
- [API Reference →](/api/filter)
