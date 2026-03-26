<?php

declare(strict_types=1);

namespace Items\Tests;

use Items\Items;
use Items\ItemsArray;
use PHPUnit\Framework\TestCase;

final class ItemsArrayTest extends TestCase
{
    public function testStaticImmutableFilterReturnsNewArray(): void
    {
        $items = [
            ['id' => 1, 'active' => true],
            ['id' => 2, 'active' => false],
            ['id' => 3, 'active' => true],
        ];

        $result = Items::filtered($items, ['active', true]);

        $this->assertCount(2, $result);
        $this->assertSame([1, 3], array_column($result, 'id'));
        $this->assertCount(3, $items);
    }

    public function testStaticInPlaceFilterMutatesInputArray(): void
    {
        $items = [
            ['id' => 1, 'score' => 80],
            ['id' => 2, 'score' => 40],
            ['id' => 3, 'score' => 60],
        ];

        Items::filter($items, ['score', '>=', 60]);

        $this->assertCount(2, $items);
        $this->assertSame([1, 3], array_column($items, 'id'));
    }

    public function testFluentFilterMutatesCurrentInstance(): void
    {
        $collection = new ItemsArray([
            ['id' => 1, 'status' => 'draft'],
            ['id' => 2, 'status' => 'published'],
            ['id' => 3, 'status' => 'published'],
        ]);

        $collection->filter(['status', 'published']);

        $this->assertSame([2, 3], array_column($collection->all(), 'id'));
    }

    public function testFluentFilteredReturnsNewInstance(): void
    {
        $collection = new ItemsArray([
            ['id' => 1, 'status' => 'draft'],
            ['id' => 2, 'status' => 'published'],
        ]);

        $newCollection = $collection->filtered(['status', 'published']);

        $this->assertInstanceOf(ItemsArray::class, $newCollection);
        $this->assertNotSame($collection, $newCollection);
        $this->assertSame([1, 2], array_column($collection->all(), 'id'));
        $this->assertSame([2], array_column($newCollection->all(), 'id'));
    }

    public function testDotNotationWorksForNestedArraysAndObjects(): void
    {
        $person = (object) [
            'name' => 'Ana',
            'address' => (object) [
                'city' => 'Lisbon',
            ],
        ];

        $items = [
            'user' => [
                'profile' => [
                    'person' => $person,
                ],
            ],
        ];

        $collection = new ItemsArray($items);

        $this->assertSame('Lisbon', $collection->get('user.profile.person.address.city'));
        $this->assertNull($collection->get('user.profile.person.address.zip'));

        $collection->set('user.profile.person.address.city', 'Porto');
        $this->assertSame('Porto', $collection->get('user.profile.person.address.city'));
    }

    public function testStaticSortingWithDotNotationField(): void
    {
        $items = [
            ['user' => ['address' => ['city' => 'Rome']]],
            ['user' => ['address' => ['city' => 'Berlin']]],
            ['user' => ['address' => ['city' => 'Madrid']]],
        ];

        $sorted = Items::sorted($items, ['user.address.city', 'ASC']);

        $this->assertSame(
            ['Berlin', 'Madrid', 'Rome'],
            array_map(static fn (array $item): string => $item['user']['address']['city'], $sorted)
        );
    }
}
