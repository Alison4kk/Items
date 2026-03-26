<?php

declare(strict_types=1);

namespace Items;

/**
 * @template T
 */
class ItemsArray
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
     * Fluent immutable filter for instance usage.
     *
     * @param callable|array ...$conditions
     * @return self<T>
     */
    public function filtered(...$conditions): self
    {
        return new self(Items::filtered($this->items, ...$conditions));
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
     * Fluent immutable sort for instance usage.
     *
     * @param callable|array ...$criteria
     * @return self<T>
     */
    public function sorted(...$criteria): self
    {
        return new self(Items::sorted($this->items, ...$criteria));
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
     * @template U
     * @param callable(T, int|string): U $mapper
     * @return self<U>
     */
    public function mapped(callable $mapper): self
    {
        /** @var self<U> $new */
        $new = new self(Items::mapped($this->items, $mapper));

        return $new;
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

    /**
     * Dot notation setter that returns a new instance.
     *
     * @param string $path
     * @param mixed $value
     * @return self<T>
     */
    public function with(string $path, $value): self
    {
        $items = $this->items;
        Items::setPath($items, $path, $value);

        return new self($items);
    }
}