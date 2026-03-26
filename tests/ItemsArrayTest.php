<?php

namespace Items\Tests;

use Items\Items;
use Items\ItemsArray;
use PHPUnit\Framework\TestCase;

class ItemsArrayTest extends TestCase
{
    private $users;

    protected function setUp(): void
    {
        $this->users = [
            ['id' => 1, 'name' => 'Alice', 'city' => 'São Paulo', 'age' => 28, 'active' => true],
            ['id' => 2, 'name' => 'Bob', 'city' => 'Rio', 'age' => 35, 'active' => false],
            ['id' => 3, 'name' => 'Charlie', 'city' => 'São Paulo', 'age' => 42, 'active' => true],
            ['id' => 4, 'name' => 'Diana', 'city' => 'Belo Horizonte', 'age' => 31, 'active' => true],
        ];
    }

    // ===== Filter Tests =====

    public function testFilteredReturnsNewArray()
    {
        $items = new ItemsArray($this->users);
        $result = $items->filtered(['city' => 'São Paulo']);
        
        $this->assertInstanceOf(ItemsArray::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals('Alice', $result->all()[0]['name']);
        $this->assertEquals('Charlie', $result->all()[1]['name']);
    }

    public function testFilterMutatesInstance()
    {
        $items = new ItemsArray($this->users);
        $returned = $items->filter(['city' => 'São Paulo']);
        
        $this->assertSame($items, $returned); // Returns same instance
        $this->assertCount(2, $items);
        $this->assertEquals('Alice', $items->all()[0]['name']);
    }

    public function testFilterMultipleConditions()
    {
        $items = new ItemsArray($this->users);
        $result = $items->filtered(['city' => 'São Paulo', 'active' => true]);
        
        $this->assertCount(2, $result); // Alice and Charlie
    }

    // ===== Sort Tests =====

    public function testSortedAscending()
    {
        $items = new ItemsArray($this->users);
        $result = $items->sorted('age', 'asc');
        
        $all = $result->all();
        $this->assertEquals(28, $all[0]['age']);
        $this->assertEquals(42, $all[3]['age']);
    }

    public function testSortedDescending()
    {
        $items = new ItemsArray($this->users);
        $result = $items->sorted('age', 'desc');
        
        $all = $result->all();
        $this->assertEquals(42, $all[0]['age']);
        $this->assertEquals(28, $all[3]['age']);
    }

    public function testSortMutatesInstance()
    {
        $items = new ItemsArray($this->users);
        $returned = $items->sort('age', 'asc');
        
        $this->assertSame($items, $returned);
        $this->assertEquals(28, $items->all()[0]['age']);
    }

    // ===== Map Tests =====

    public function testMappedTransformsValues()
    {
        $items = new ItemsArray($this->users);
        $result = $items->mapped(fn($user) => $user['name']);
        
        $all = $result->all();
        $this->assertCount(4, $all);
        $this->assertEquals('Alice', $all[0]);
        $this->assertEquals('Diana', $all[3]);
    }

    public function testMapMutatesInstance()
    {
        $items = new ItemsArray($this->users);
        $returned = $items->map(fn($user) => strtoupper($user['name']));
        
        $this->assertSame($items, $returned);
        $this->assertEquals('ALICE', $items->all()[0]);
    }

    // ===== Unique Tests =====

    public function testUniquedByKey()
    {
        $items = [
            ['id' => 1, 'city' => 'São Paulo'],
            ['id' => 2, 'city' => 'Rio'],
            ['id' => 3, 'city' => 'São Paulo'],
        ];
        $result = (new ItemsArray($items))->uniqued('city');
        
        $this->assertCount(2, $result);
        $this->assertEquals('São Paulo', $result->all()[0]['city']);
        $this->assertEquals('Rio', $result->all()[1]['city']);
    }

    public function testUniqueMutatesInstance()
    {
        $items = [
            ['id' => 1, 'city' => 'São Paulo'],
            ['id' => 2, 'city' => 'Rio'],
            ['id' => 3, 'city' => 'São Paulo'],
        ];
        $arr = new ItemsArray($items);
        $returned = $arr->unique('city');
        
        $this->assertSame($arr, $returned);
        $this->assertCount(2, $arr);
    }

    // ===== Indexed Tests =====

    public function testIndexedByField()
    {
        $items = new ItemsArray($this->users);
        $result = $items->indexed('id');
        
        $this->assertIsArray($result);
        $this->assertTrue(isset($result[1])); // Key 1 => Alice's data
        $this->assertEquals('Alice', $result[1]['name']);
    }

    // ===== Grouped Tests =====

    public function testGroupedByField()
    {
        $items = new ItemsArray($this->users);
        $result = $items->grouped('city');
        
        $this->assertIsArray($result);
        $this->assertCount(2, $result['São Paulo']);
        $this->assertCount(1, $result['Rio']);
        $this->assertCount(1, $result['Belo Horizonte']);
    }

    // ===== Find Tests =====

    public function testFirstWithCondition()
    {
        $items = new ItemsArray($this->users);
        $result = $items->first(['city' => 'São Paulo']);
        
        $this->assertIsArray($result);
        $this->assertEquals('Alice', $result['name']);
    }

    public function testFirstWithoutCondition()
    {
        $items = new ItemsArray($this->users);
        $result = $items->first();
        
        $this->assertIsArray($result);
        $this->assertEquals('Alice', $result['name']);
    }

    public function testFirstWithCallable()
    {
        $items = new ItemsArray($this->users);
        $result = $items->first(fn($user) => $user['age'] > 40);
        
        $this->assertIsArray($result);
        $this->assertEquals('Charlie', $result['name']);
    }

    public function testLastWithCondition()
    {
        $items = new ItemsArray($this->users);
        $result = $items->last(['city' => 'São Paulo']);
        
        $this->assertIsArray($result);
        $this->assertEquals('Charlie', $result['name']);
    }

    public function testLastWithoutCondition()
    {
        $items = new ItemsArray($this->users);
        $result = $items->last();
        
        $this->assertIsArray($result);
        $this->assertEquals('Diana', $result['name']);
    }

    // ===== Every/Some Tests =====

    public function testEveryReturnsTrueWhenAllMatch()
    {
        $items = new ItemsArray($this->users);
        $result = $items->every(fn($user) => isset($user['name']));
        
        $this->assertTrue($result);
    }

    public function testEveryReturnsFalseWhenSomeFail()
    {
        $items = new ItemsArray($this->users);
        $result = $items->every(['active' => true]);
        
        $this->assertFalse($result); // Bob is inactive
    }

    public function testSomeReturnsTrueWhenAnyMatch()
    {
        $items = new ItemsArray($this->users);
        $result = $items->some(['city' => 'Rio']);
        
        $this->assertTrue($result);
    }

    public function testSomeReturnsFalseWhenNoneMatch()
    {
        $items = new ItemsArray($this->users);
        $result = $items->some(['city' => 'Brasília']);
        
        $this->assertFalse($result);
    }

    // ===== Count Tests =====

    public function testCountWithoutCondition()
    {
        $items = new ItemsArray($this->users);
        $result = $items->count();
        
        $this->assertEquals(4, $result);
    }

    public function testCountWithCondition()
    {
        $items = new ItemsArray($this->users);
        $result = $items->count(['active' => true]);
        
        $this->assertEquals(3, $result); // Alice, Charlie, Diana
    }

    // ===== Contains Test =====

    public function testContainsReturnsTrueWhenExists()
    {
        $items = new ItemsArray($this->users);
        $result = $items->contains(['name' => 'Alice']);
        
        $this->assertTrue($result);
    }

    public function testContainsReturnsFalseWhenNotExists()
    {
        $items = new ItemsArray($this->users);
        $result = $items->contains(['name' => 'Eve']);
        
        $this->assertFalse($result);
    }

    // ===== Aggregation Tests =====

    public function testSum()
    {
        $items = new ItemsArray($this->users);
        $result = $items->sum('age');
        
        $this->assertEquals(136, $result); // 28 + 35 + 42 + 31
    }

    public function testAverage()
    {
        $items = new ItemsArray($this->users);
        $result = $items->average('age');
        
        $this->assertEquals(34, $result); // 136 / 4
    }

    public function testMax()
    {
        $items = new ItemsArray($this->users);
        $result = $items->max('age');
        
        $this->assertEquals(42, $result);
    }

    public function testMin()
    {
        $items = new ItemsArray($this->users);
        $result = $items->min('age');
        
        $this->assertEquals(28, $result);
    }

    // ===== Group Aggregates =====

    public function testCountBy()
    {
        $items = new ItemsArray($this->users);
        $result = $items->countBy('city');
        
        $this->assertEquals(2, $result['São Paulo']);
        $this->assertEquals(1, $result['Rio']);
        $this->assertEquals(1, $result['Belo Horizonte']);
    }

    public function testSumBy()
    {
        $items = new ItemsArray($this->users);
        $result = $items->sumBy('city', 'age');
        
        $this->assertEquals(70, $result['São Paulo']); // 28 + 42
        $this->assertEquals(35, $result['Rio']); // Bob
        $this->assertEquals(31, $result['Belo Horizonte']); // Diana
    }

    // ===== Transform Tests =====

    public function testKeyValue()
    {
        $items = [
            ['id' => 1, 'name' => 'Alice'],
            ['id' => 2, 'name' => 'Bob'],
        ];
        $result = (new ItemsArray($items))->keyValue('id', 'name');
        
        $this->assertEquals([1 => 'Alice', 2 => 'Bob'], $result);
    }

    public function testMapKeys()
    {
        $items = [
            ['id' => 1, 'name' => 'Alice'],
            ['id' => 2, 'name' => 'Bob'],
        ];
        $result = (new ItemsArray($items))->mapKeys(fn($key, $item) => $item['id']);
        
        $this->assertEquals([1 => $items[0], 2 => $items[1]], $result);
    }

    // ===== Dot Notation Tests =====

    public function testDotNotationGet()
    {
        $data = [
            'user' => [
                'profile' => [
                    'name' => 'Alice'
                ]
            ]
        ];
        $items = new ItemsArray([$data]);
        $value = $items->get('0.user.profile.name');
        
        $this->assertEquals('Alice', $value);
    }

    public function testDotNotationSet()
    {
        $data = ['user' => ['name' => 'Bob']];
        $items = new ItemsArray([$data]);
        $items->set('0.user.city', 'São Paulo');
        
        $all = $items->all();
        $this->assertEquals('São Paulo', $all[0]['user']['city']);
    }

    public function testDotNotationWith()
    {
        $data = ['user' => ['name' => 'Charlie']];
        $items = new ItemsArray([$data]);
        $result = $items->with('0.user.age', 42);
        
        $this->assertInstanceOf(ItemsArray::class, $result);
        $all = $result->all();
        $this->assertEquals(42, $all[0]['user']['age']);
    }

    // ===== Fluent Chaining Tests =====

    public function testFluentChaining()
    {
        $result = (new ItemsArray($this->users))
            ->filter(['active' => true])
            ->sorted('age', 'asc')
            ->mapped(fn($user) => ['name' => $user['name'], 'age' => $user['age']]);
        
        $all = $result->all();
        $this->assertCount(3, $all); // Alice, Charlie, Diana (active users)
        $this->assertEquals('Alice', $all[0]['name']); // Youngest active
        $this->assertEquals(28, $all[0]['age']);
    }

    public function testFluentChainingMixedMutable()
    {
        $items = new ItemsArray($this->users);
        $result = $items
            ->filter(['active' => true]) // Mutates $items
            ->sorted('name', 'asc'); // Returns new sorted ItemsArray
        
        // $items should be filtered (mutated in-place)
        $this->assertCount(3, $items);
        // $result should be sorted
        $this->assertEquals('Alice', $result->all()[0]['name']);
    }

    // ===== Static API Tests =====

    public function testStaticFiltered()
    {
        $result = Items::filtered($this->users, ['active' => true]);
        
        $this->assertCount(3, $result);
        $this->assertEquals('Alice', $result[0]['name']);
    }

    public function testStaticFilter()
    {
        $items = $this->users;
        Items::filter($items, ['city' => 'Rio']);
        
        $this->assertCount(1, $items);
        $this->assertEquals('Bob', $items[0]['name']);
    }

    public function testStaticSorted()
    {
        $result = Items::sorted($this->users, 'age', 'desc');
        
        $this->assertEquals(42, $result[0]['age']);
        $this->assertEquals(28, $result[3]['age']);
    }

    public function testStaticMapped()
    {
        $result = Items::mapped($this->users, fn($user) => $user['age']);
        
        $this->assertEquals([28, 35, 42, 31], $result);
    }

    // ===== Test All Methods =====

    public function testConstructorWithArray()
    {
        $items = new ItemsArray($this->users);
        $this->assertCount(4, $items);
    }

    public function testConstructorEmpty()
    {
        $items = new ItemsArray();
        $this->assertCount(0, $items);
    }

    public function testToArray()
    {
        $items = new ItemsArray($this->users);
        $array = $items->toArray();
        
        $this->assertEquals($this->users, $array);
    }

    public function testAll()
    {
        $items = new ItemsArray($this->users);
        $all = $items->all();
        
        $this->assertEquals($this->users, $all);
    }
}
