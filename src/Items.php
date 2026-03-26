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

        if (!is_array($condition) || !isset($condition[0])) {
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