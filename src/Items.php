<?php

declare(strict_types=1);

namespace Items;

/**
 * @template T
 */
class Items
{
    /**
     * Static immutable filter.
     *
     * @template U
     * @param array<U> $items
     * @param callable|array ...$conditions
     * @return array<U>
     */
    public static function filtered(array $items, ...$conditions): array
    {
        if (count($conditions) === 0) {
            return array_values($items);
        }

        return array_values(array_filter($items, function ($item) use ($conditions): bool {
            foreach ($conditions as $condition) {
                if (!self::matchesCondition($item, $condition)) {
                    return false;
                }
            }

            return true;
        }));
    }

    /**
     * Static in-place filter.
     *
     * @template U
     * @param array<U> $items
     * @param callable|array ...$conditions
     */
    public static function filter(array &$items, ...$conditions): void
    {
        $items = self::filtered($items, ...$conditions);
    }

    /**
     * Static immutable sort.
     *
     * @template U
     * @param array<U> $items
     * @param callable|array ...$criteria
     * @return array<U>
     */
    public static function sorted(array $items, ...$criteria): array
    {
        $sorted = $items;
        self::sort($sorted, ...$criteria);

        return $sorted;
    }

    /**
     * Static in-place sort.
     *
     * @template U
     * @param array<U> $items
     * @param callable|array ...$criteria
     */
    public static function sort(array &$items, ...$criteria): void
    {
        if (count($criteria) === 0) {
            return;
        }

        if (is_callable($criteria[0])) {
            usort($items, $criteria[0]);
            return;
        }

        // Handle string arguments like sort($items, 'field', 'asc')
        if (count($criteria) >= 2 && is_string($criteria[0]) && is_string($criteria[1])) {
            $field = (string)$criteria[0];
            $direction = strtoupper((string)$criteria[1]);
            usort($items, function ($left, $right) use ($field, $direction): int {
                $leftValue = self::getPath($left, $field);
                $rightValue = self::getPath($right, $field);
                $comparison = $leftValue <=> $rightValue;
                if ($comparison !== 0) {
                    return $direction === 'DESC' ? -$comparison : $comparison;
                }
                return 0;
            });
            return;
        }

        usort($items, function ($left, $right) use ($criteria): int {
            foreach ($criteria as $criterion) {
                $field = (string)($criterion[0] ?? '');
                $direction = strtoupper((string)($criterion[1] ?? 'ASC'));

                $leftValue = self::getPath($left, $field);
                $rightValue = self::getPath($right, $field);

                $comparison = $leftValue <=> $rightValue;
                if ($comparison !== 0) {
                    return $direction === 'DESC' ? -$comparison : $comparison;
                }
            }

            return 0;
        });
    }

    /**
     * Static immutable map.
     *
     * @template U
     * @template V
     * @param array<U> $items
     * @param callable(U, int|string): V $mapper
     * @return array<V>
     */
    public static function mapped(array $items, callable $mapper): array
    {
        $mapped = [];
        foreach ($items as $key => $item) {
            $mapped[] = $mapper($item, $key);
        }

        return $mapped;
    }

    /**
     * Static in-place map.
     *
     * @template U
     * @template V
     * @param array<U|V> $items
     * @param callable(U, int|string): V $mapper
     */
    public static function map(array &$items, callable $mapper): void
    {
        $items = self::mapped($items, $mapper);
    }

    /**
     * Static immutable unique items by field/callable.
     *
     * @template U
     * @param array<U> $items
     * @param string|callable(U): mixed $key
     * @return array<U>
     */
    public static function uniqued(array $items, $key): array
    {
        $seen = [];
        $result = [];

        foreach ($items as $item) {
            if (is_callable($key)) {
                $value = $key($item);
            } else {
                $value = self::getPath($item, (string)$key);
            }

            $hash = serialize($value);
            if (!isset($seen[$hash])) {
                $seen[$hash] = true;
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * Static in-place unique.
     *
     * @template U
     * @param array<U> $items
     * @param string|callable(U): mixed $key
     */
    public static function unique(array &$items, $key): void
    {
        $items = self::uniqued($items, $key);
    }

    /**
     * Index array by field/callable.
     *
     * @template U
     * @param array<U> $items
     * @param string|callable(U): (string|int) $key
     * @return array<string|int, U>
     */
    public static function indexed(array $items, $key): array
    {
        $result = [];

        foreach ($items as $item) {
            if (is_callable($key)) {
                $index = $key($item);
            } else {
                $index = self::getPath($item, (string)$key);
            }

            if ($index !== null) {
                $result[$index] = $item;
            }
        }

        return $result;
    }

    /**
     * Group array by field/callable.
     *
     * @template U
     * @param array<U> $items
     * @param string|callable(U): (string|int) $key
     * @param string|callable(U): (string|int)|null $subKey
     * @return array<string|int, array<U>|array<string|int, array<U>>>
     */
    public static function grouped(array $items, $key, $subKey = null): array
    {
        $result = [];

        foreach ($items as $item) {
            if (is_callable($key)) {
                $mainKey = $key($item);
            } else {
                $mainKey = self::getPath($item, (string)$key);
            }

            if ($mainKey === null) {
                continue;
            }

            if ($subKey === null) {
                if (!isset($result[$mainKey])) {
                    $result[$mainKey] = [];
                }
                $result[$mainKey][] = $item;
            } else {
                if (is_callable($subKey)) {
                    $secondaryKey = $subKey($item);
                } else {
                    $secondaryKey = self::getPath($item, (string)$subKey);
                }

                if ($secondaryKey === null) {
                    continue;
                }

                if (!isset($result[$mainKey])) {
                    $result[$mainKey] = [];
                }
                if (!isset($result[$mainKey][$secondaryKey])) {
                    $result[$mainKey][$secondaryKey] = [];
                }
                $result[$mainKey][$secondaryKey][] = $item;
            }
        }

        return $result;
    }

    /**
     * Index array in-place by field/callable.
     *
     * @template U
     * @param array<U> $items
     * @param string|callable(U): (string|int) $key
     */
    public static function index(array &$items, $key): void
    {
        $items = self::indexed($items, $key);
    }

    /**
     * Group array in-place by field/callable.
     *
     * @template U
     * @param array<U> $items
     * @param string|callable(U): (string|int) $key
     * @param string|callable(U): (string|int)|null $subKey
     */
    public static function group(array &$items, $key, $subKey = null): void
    {
        $items = self::grouped($items, $key, $subKey);
    }

    /**
     * Extract a column from items.
     *
     * @template U
     * @param array<U> $items
     * @param string|callable(U): mixed $key
     * @return array<mixed>
     */
    public static function column(array $items, $key): array
    {
        $result = [];

        foreach ($items as $item) {
            if (is_callable($key)) {
                $value = $key($item);
            } else {
                $value = self::getPath($item, (string)$key);
            }

            if ($value !== null) {
                $result[] = $value;
            }
        }

        return $result;
    }

    /**
     * Find first item matching condition.
     *
     * @template U
     * @param array<U> $items
     * @param callable(U): bool|array $condition
     * @return U|null
     */
    public static function first(array $items, $condition = null)
    {
        if ($condition === null) {
            return reset($items) ?: null;
        }

        foreach ($items as $item) {
            if (self::matchesCondition($item, $condition)) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Find last item matching condition.
     *
     * @template U
     * @param array<U> $items
     * @param callable(U): bool|array $condition
     * @return U|null
     */
    public static function last(array $items, $condition = null)
    {
        if ($condition === null) {
            return end($items) ?: null;
        }

        $result = null;
        foreach ($items as $item) {
            if (self::matchesCondition($item, $condition)) {
                $result = $item;
            }
        }

        return $result;
    }

    /**
     * Check if all items match condition.
     *
     * @template U
     * @param array<U> $items
     * @param callable(U): bool|array $condition
     * @return bool
     */
    public static function every(array $items, $condition): bool
    {
        foreach ($items as $item) {
            if (!self::matchesCondition($item, $condition)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if any item matches condition.
     *
     * @template U
     * @param array<U> $items
     * @param callable(U): bool|array $condition
     * @return bool
     */
    public static function some(array $items, $condition): bool
    {
        foreach ($items as $item) {
            if (self::matchesCondition($item, $condition)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Count items matching condition.
     *
     * @template U
     * @param array<U> $items
     * @param callable(U): bool|array $condition
     * @return int
     */
    public static function count(array $items, $condition = null): int
    {
        if ($condition === null) {
            return count($items);
        }

        $count = 0;
        foreach ($items as $item) {
            if (self::matchesCondition($item, $condition)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Check if array contains item matching fields.
     *
     * @template U
     * @param array<U> $items
     * @param array<string, mixed> $search
     * @return bool
     */
    public static function contains(array $items, array $search): bool
    {
        foreach ($items as $item) {
            $matches = true;
            foreach ($search as $field => $value) {
                if (self::getPath($item, $field) !== $value) {
                    $matches = false;
                    break;
                }
            }
            if ($matches) {
                return true;
            }
        }

        return false;
    }

    /**
     * Sum values from field.
     *
     * @template U
     * @param array<U> $items
     * @param string $field
     * @return float|int
     */
    public static function sum(array $items, string $field)
    {
        $sum = 0;
        foreach ($items as $item) {
            $sum += self::getPath($item, $field) ?? 0;
        }

        return $sum;
    }

    /**
     * Average of field values.
     *
     * @template U
     * @param array<U> $items
     * @param string $field
     * @return float|int
     */
    public static function average(array $items, string $field)
    {
        if (empty($items)) {
            return 0;
        }

        return self::sum($items, $field) / count($items);
    }

    /**
     * Maximum value of field.
     *
     * @template U
     * @param array<U> $items
     * @param string $field
     * @return mixed
     */
    public static function max(array $items, string $field)
    {
        if (empty($items)) {
            return null;
        }

        $max = null;
        foreach ($items as $item) {
            $value = self::getPath($item, $field);
            if ($max === null || $value > $max) {
                $max = $value;
            }
        }

        return $max;
    }

    /**
     * Minimum value of field.
     *
     * @template U
     * @param array<U> $items
     * @param string $field
     * @return mixed
     */
    public static function min(array $items, string $field)
    {
        if (empty($items)) {
            return null;
        }

        $min = null;
        foreach ($items as $item) {
            $value = self::getPath($item, $field);
            if ($min === null || $value < $min) {
                $min = $value;
            }
        }

        return $min;
    }

    /**
     * Count occurrences by field.
     *
     * @template U
     * @param array<U> $items
     * @param string $field
     * @return array<string|int, int>
     */
    public static function countBy(array $items, string $field): array
    {
        $result = [];

        foreach ($items as $item) {
            $key = self::getPath($item, $field);
            if ($key !== null) {
                if (!isset($result[$key])) {
                    $result[$key] = 0;
                }
                $result[$key]++;
            }
        }

        return $result;
    }

    /**
     * Sum by group field.
     *
     * @template U
     * @param array<U> $items
     * @param string $groupField
     * @param string $sumField
     * @return array<string|int, float|int>
     */
    public static function sumBy(array $items, string $groupField, string $sumField): array
    {
        $result = [];

        foreach ($items as $item) {
            $key = self::getPath($item, $groupField);
            if ($key !== null) {
                if (!isset($result[$key])) {
                    $result[$key] = 0;
                }
                $result[$key] += self::getPath($item, $sumField) ?? 0;
            }
        }

        return $result;
    }

    /**
     * Convert to key-value pairs.
     *
     * @template U
     * @param array<U> $items
     * @param string $keyField
     * @param string $valueField
     * @return array<string|int, mixed>
     */
    public static function keyValue(array $items, string $keyField, string $valueField): array
    {
        $result = [];

        foreach ($items as $item) {
            $key = self::getPath($item, $keyField);
            if ($key !== null) {
                $result[$key] = self::getPath($item, $valueField);
            }
        }

        return $result;
    }

    /**
     * Map keys using transformer.
     *
     * @template U
     * @param array<string|int, U> $items
     * @param callable(string|int, U): (string|int) $mapper
     * @return array<string|int, U>
     */
    public static function mapKeys(array $items, callable $mapper): array
    {
        $result = [];

        foreach ($items as $key => $item) {
            $newKey = $mapper($key, $item);
            $result[$newKey] = $item;
        }

        return $result;
    }

    /**
     * Dot notation getter for arrays/objects.
     *
     * @param array|object $target
     * @param string $path
     * @param mixed $default
     * @return mixed
     */
    public static function getPath($target, string $path, $default = null)
    {
        $found = false;
        $value = self::resolvePath($target, $path, $found);

        return $found ? $value : $default;
    }

    /**
     * Dot notation setter for arrays/objects.
     *
     * @param array|object $target
     * @param string $path
     * @param mixed $value
     */
    public static function setPath(&$target, string $path, $value): void
    {
        if ($path === '') {
            $target = $value;
            return;
        }

        $segments = explode('.', $path);
        self::writePath($target, $segments, $value);
    }

    /**
     * @param mixed $item
     * @param callable|array $condition
     */
    private static function matchesCondition($item, $condition): bool
    {
        if (is_callable($condition)) {
            return (bool) $condition($item);
        }

        if (!is_array($condition)) {
            return false;
        }

        // Handle associative array conditions like ['field' => 'value', 'field2' => 'value2']
        $keys = array_keys($condition);
        if (!isset($keys[0]) || !is_int($keys[0])) {
            // Associative array: treat as multiple field=value conditions (ALL must match)
            foreach ($condition as $field => $value) {
                if (self::getPath($item, (string)$field) != $value) {
                    return false;
                }
            }
            return true;
        }

        // Handle indexed array conditions like ['field', value] or ['field', '==', value]
        if (!isset($condition[0])) {
            return false;
        }

        $field = (string)$condition[0];

        if (count($condition) === 2) {
            return self::getPath($item, $field) == $condition[1];
        }

        $operator = strtoupper((string)($condition[1] ?? '=='));
        $value = $condition[2] ?? null;
        $current = self::getPath($item, $field);

        switch ($operator) {
            case '=':
            case '==':
                return $current == $value;

            case '===':
                return $current === $value;

            case '!=':
            case '<>':
                return $current != $value;

            case '!==':
                return $current !== $value;

            case '>':
                return $current > $value;

            case '>=':
                return $current >= $value;

            case '<':
                return $current < $value;

            case '<=':
                return $current <= $value;

            case 'IN':
                return is_array($value) && in_array($current, $value, true);

            case '!IN':
                return is_array($value) && !in_array($current, $value, true);

            case 'CONTAINS':
                return is_string($current) && mb_strpos($current, (string)$value) !== false;

            case '!CONTAINS':
                return !is_string($current) || mb_strpos($current, (string)$value) === false;
        }

        return false;
    }

    /**
     * @param mixed $target
     * @param string $path
     * @param bool $found
     * @return mixed
     */
    private static function resolvePath($target, string $path, bool &$found)
    {
        if ($path === '') {
            $found = true;
            return $target;
        }

        $cursor = $target;
        $segments = explode('.', $path);

        foreach ($segments as $segment) {
            if (is_array($cursor) && array_key_exists($segment, $cursor)) {
                $cursor = $cursor[$segment];
                continue;
            }

            if (is_object($cursor) && (property_exists($cursor, $segment) || isset($cursor->{$segment}))) {
                $cursor = $cursor->{$segment};
                continue;
            }

            $found = false;
            return null;
        }

        $found = true;

        return $cursor;
    }

    /**
     * @param mixed $target
     * @param array<int, string> $segments
     * @param mixed $value
     */
    private static function writePath(&$target, array $segments, $value): void
    {
        $segment = array_shift($segments);
        if ($segment === null) {
            $target = $value;
            return;
        }

        if (count($segments) === 0) {
            if (is_array($target)) {
                $target[$segment] = $value;
                return;
            }

            if (is_object($target)) {
                $target->{$segment} = $value;
                return;
            }

            $target = [$segment => $value];
            return;
        }

        if (is_array($target)) {
            if (!array_key_exists($segment, $target) || (!is_array($target[$segment]) && !is_object($target[$segment]))) {
                $target[$segment] = [];
            }

            self::writePath($target[$segment], $segments, $value);
            return;
        }

        if (is_object($target)) {
            if (!property_exists($target, $segment) || (!is_array($target->{$segment}) && !is_object($target->{$segment}))) {
                $target->{$segment} = [];
            }

            self::writePath($target->{$segment}, $segments, $value);
            return;
        }

        $target = [];
        self::writePath($target, array_merge([$segment], $segments), $value);
    }
}