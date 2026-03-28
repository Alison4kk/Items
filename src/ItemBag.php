<?php

declare(strict_types=1);

namespace Items;

use Countable;

/**
 * @template T
 */
class ItemBag implements Countable
{
    /**
     * @var array<T>
     */
    private array $items;

    /**
     * @param array<T> $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Create ItemBag from array.
     *
     * @template T
     * @param array<T> $array
     * @return self<T>
     */
    public static function from(array $array): self
    {
        return new self($array);
    }

    /**
     * @return array<T>
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * @return array<T>
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * Fluent in-place filter for instance usage.
     *
     * @param callable|array ...$conditions
     * @return $this
     */
    public function filter(...$conditions): self
    {
        Items::filter($this->items, ...$conditions);

        return $this;
    }

    /**
     * Fluent in-place sort for instance usage.
     *
     * @param callable|array ...$criteria
     * @return $this
     */
    public function sort(...$criteria): self
    {
        Items::sort($this->items, ...$criteria);

        return $this;
    }

    /**
     * @template U
     * @param callable(T, int|string): U $mapper
     * @return $this
     */
    public function map(callable $mapper): self
    {
        Items::map($this->items, $mapper);

        return $this;
    }

    /**
     * Fluent in-place unique.
     *
     * @param string|callable(T): mixed $key
     * @return $this
     */
    public function unique($key): self
    {
        Items::unique($this->items, $key);

        return $this;
    }

    /**
     * Index items in-place by field/callable.
     *
     * @param string|callable(T): (string|int) $key
     * @return $this
     */
    public function index($key): self
    {
        Items::index($this->items, $key);
        return $this;
    }

    /**
     * Group items in-place by field/callable.
     *
     * @param string|callable(T): (string|int) $key
     * @param string|callable(T): (string|int)|null $subKey
     * @return $this
     */
    public function group($key, $subKey = null): self
    {
        Items::group($this->items, $key, $subKey);
        return $this;
    }

    /**
     * Extract a column from items.
     *
     * @param string|callable(T): mixed $key
     * @return array<mixed>
     */
    public function column($key): array
    {
        return Items::column($this->items, $key);
    }

    /**
     * Find first item.
     *
     * @param callable(T): bool|array $condition
     * @return T|null
     */
    public function first($condition = null)
    {
        return Items::first($this->items, $condition);
    }

    /**
     * Find last item.
     *
     * @param callable(T): bool|array $condition
     * @return T|null
     */
    public function last($condition = null)
    {
        return Items::last($this->items, $condition);
    }

    /**
     * Check if all items match condition.
     *
     * @param callable(T): bool|array $condition
     * @return bool
     */
    public function every($condition): bool
    {
        return Items::every($this->items, $condition);
    }

    /**
     * Check if any item matches condition.
     *
     * @param callable(T): bool|array $condition
     * @return bool
     */
    public function some($condition): bool
    {
        return Items::some($this->items, $condition);
    }

    /**
     * Count items.
     *
     * @param callable(T): bool|array|null $condition
     * @return int
     */
    public function count($condition = null): int
    {
        return Items::count($this->items, $condition);
    }

    /**
     * Check if contains items matching fields.
     *
     * @param array<string, mixed> $search
     * @return bool
     */
    public function contains(array $search): bool
    {
        return Items::contains($this->items, $search);
    }

    /**
     * Sum values from field.
     *
     * @param string $field
     * @return float|int
     */
    public function sum(string $field)
    {
        return Items::sum($this->items, $field);
    }

    /**
     * Average of field values.
     *
     * @param string $field
     * @return float|int
     */
    public function average(string $field)
    {
        return Items::average($this->items, $field);
    }

    /**
     * Maximum value of field.
     *
     * @param string $field
     * @return mixed
     */
    public function max(string $field)
    {
        return Items::max($this->items, $field);
    }

    /**
     * Minimum value of field.
     *
     * @param string $field
     * @return mixed
     */
    public function min(string $field)
    {
        return Items::min($this->items, $field);
    }

    /**
     * Count occurrences by field.
     *
     * @param string $field
     * @return array<string|int, int>
     */
    public function countBy(string $field): array
    {
        return Items::countBy($this->items, $field);
    }

    /**
     * Sum by group field.
     *
     * @param string $groupField
     * @param string $sumField
     * @return array<string|int, float|int>
     */
    public function sumBy(string $groupField, string $sumField): array
    {
        return Items::sumBy($this->items, $groupField, $sumField);
    }

    /**
     * Convert to key-value pairs.
     *
     * @param string $keyField
     * @param string $valueField
     * @return array<string|int, mixed>
     */
    public function keyValue(string $keyField, string $valueField): array
    {
        return Items::keyValue($this->items, $keyField, $valueField);
    }

    /**
     * Map keys using transformer.
     *
     * @param callable(string|int, T): (string|int) $mapper
     * @return array<string|int, T>
     */
    public function mapKeys(callable $mapper): array
    {
        return Items::mapKeys($this->items, $mapper);
    }

    /**
     * Dot notation getter from instance payload.
     *
     * @param string $path
     * @param mixed $default
     * @return mixed
     */
    public function get(string $path, $default = null)
    {
        return Items::getPath($this->items, $path, $default);
    }

    /**
     * Dot notation setter that mutates current instance.
     *
     * @param string $path
     * @param mixed $value
     * @return $this
     */
    public function set(string $path, $value): self
    {
        Items::setPath($this->items, $path, $value);

        return $this;
    }
}