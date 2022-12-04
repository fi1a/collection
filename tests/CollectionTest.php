<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection;

use Fi1a\Collection\Collection;
use Fi1a\Collection\CollectionInterface;
use Fi1a\Collection\Exception\ExtractValueException;
use Fi1a\Collection\Exception\InvalidArgumentException;
use Fi1a\Unit\Collection\Fixtures\FixtureInstanceCollection;
use PHPUnit\Framework\TestCase;

/**
 * Тестироввние методов коллекции
 */
class CollectionTest extends TestCase
{
    /**
     * Тестирование методов
     */
    public function testConstruct(): void
    {
        $collection = new Collection('mixed');
        $collection->set(0, 1);
        $collection->set(3, 3);
        $collection->set(4, 4);
        $this->assertTrue($collection->has(0));
        $this->assertEquals(1, $collection->get(0));
        $this->assertEquals(2, $collection->get(1, 2));
        $collection->delete(0);
        $this->assertFalse($collection->has(0));
        $collection->delete(0);
        $this->assertFalse($collection->has(0));
        $counter = 0;
        $collection->each(function ($index, $element) use (&$counter) {
            $counter++;
        });
        $this->assertEquals(2, $counter);
        $collection->map(function ($element, $index) {
            return $element + 1;
        });
        $this->assertEquals([3 => 4, 4 => 5,], $collection->getArrayCopy());
    }

    /**
     * Тестирование метода add коллекции
     */
    public function testAdd(): void
    {
        $collection = new Collection('int');
        $collection->add(1);
        $collection->add(2);
        $collection->add(3);
        $this->assertEquals([0 => 1, 1 => 2, 2 => 3,], $collection->getArrayCopy());
    }

    /**
     * Тестирование метода count коллекции
     */
    public function testCount(): void
    {
        $collection = new Collection('int');
        $this->assertEquals(0, $collection->count());
        $collection->add(1);
        $collection->add(2);
        $collection->add(3);
        $this->assertEquals(3, $collection->count());
    }

    /**
     * Тестирование метода contains коллекции
     */
    public function testHasValue(): void
    {
        $collection = new Collection('int');
        $collection->add(1);
        $collection->add(2);
        $collection->add(3);
        $this->assertTrue($collection->hasValue(1));
        $this->assertTrue($collection->hasValue(2));
        $this->assertTrue($collection->hasValue(3));
        $this->assertFalse($collection->hasValue(4));
    }

    /**
     * Тестирование метода column коллекции
     */
    public function testColumn(): void
    {
        $collection = new Collection('array');
        $collection->add(['foo' => 1,]);
        $collection->add(['foo' => 2,]);
        $collection->add(['foo' => 3,]);
        $this->assertEquals([1, 2, 3,], $collection->column('foo'));

        $collection = new FixtureInstanceCollection();
        $collection->add(1);
        $collection->add(2);
        $collection->add(3);
        $this->assertEquals([1, 2, 3,], $collection->column('value'));
        $this->assertEquals([1, 2, 3,], $collection->column('getValue'));
        $this->expectException(ExtractValueException::class);
        $collection->column('fooBar');
    }

    /**
     * Тестирование метода sort коллекции
     */
    public function testSort(): void
    {
        $collection = new Collection('array');
        $collection->add(['foo' => 3,]);
        $collection->add(['foo' => 2,]);
        $collection->add(['foo' => 1,]);
        $sorted = $collection->sort('foo', CollectionInterface::SORT_ASC);
        $this->assertEquals([1, 2, 3,], $sorted->column('foo'));
        $this->assertEquals([3, 2, 1,], $collection->column('foo'));
        $sorted = $collection->sort('foo', CollectionInterface::SORT_DESC);
        $this->assertEquals([3, 2, 1,], $sorted->column('foo'));
        $this->assertEquals([3, 2, 1,], $collection->column('foo'));
    }

    /**
     * Исключение при не известном напрмарвлении сортировки
     */
    public function testSortOrderException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $collection = new Collection('int');
        $collection->add(['foo' => 3,]);
        $collection->add(['foo' => 2,]);
        $collection->add(['foo' => 1,]);
        $collection->sort('foo', 'unknown');
    }

    /**
     * Фильтрация значений коллекции
     */
    public function testFilter(): void
    {
        $collection = new Collection('array');
        $collection->add(['foo' => 3,]);
        $collection->add(['foo' => 2,]);
        $collection->add(['foo' => 1,]);
        $filtered = $collection->filter(function ($item) {
            return $item['foo'] >= 2;
        });
        $this->assertEquals([['foo' => 3,], ['foo' => 2,]], $filtered->getArrayCopy());
        $this->assertEquals([['foo' => 3,], ['foo' => 2,], ['foo' => 1,]], $collection->getArrayCopy());
    }

    /**
     * Возвразает коллекцию с элементами у которых значение ключа, свойства или метода равно переданному значению
     */
    public function testWhere(): void
    {
        $collection = new Collection('array');
        $collection->add(['foo' => 3,]);
        $collection->add(['foo' => 2,]);
        $collection->add(['foo' => 1,]);
        $filtered = $collection->where('foo', 2);
        $this->assertEquals([1 => ['foo' => 2,]], $filtered->getArrayCopy());
    }

    /**
     * Вычисление расхождения коллекций с массивами
     */
    public function testDiff(): void
    {
        $collection1 = new Collection('array');
        $collection1->add(['foo' => 3,]);
        $collection1->add(['foo' => 2,]);
        $collection1->add(['foo' => 1,]);
        $collection2 = new Collection('array');
        $collection2->add(['foo' => 4,]);
        $collection2->add(['foo' => 2,]);
        $collection2->add(['foo' => 1,]);
        $this->assertEquals([['foo' => 3,], ['foo' => 4,]], $collection1->diff($collection2)->getArrayCopy());
        $this->assertEquals([['foo' => 3,], ['foo' => 2,], ['foo' => 1,]], $collection1->getArrayCopy());
        $this->assertEquals([['foo' => 4,], ['foo' => 2,], ['foo' => 1,]], $collection2->getArrayCopy());
    }

    /**
     * Вычисляет пересечение коллекций с массивами
     */
    public function testIntersect(): void
    {
        $collection1 = new Collection('array');
        $collection1->add(['foo' => 3,]);
        $collection1->add(['foo' => 2,]);
        $collection1->add(['foo' => 1,]);
        $collection2 = new Collection('array');
        $collection2->add(['foo' => 4,]);
        $collection2->add(['foo' => 2,]);
        $collection2->add(['foo' => 1,]);
        $this->assertEquals(
            [1 => ['foo' => 2,], 2 => ['foo' => 1,]],
            $collection1->intersect($collection2)->getArrayCopy()
        );
        $this->assertEquals(
            [['foo' => 3,], ['foo' => 2,], ['foo' => 1,]],
            $collection1->getArrayCopy()
        );
        $this->assertEquals(
            [['foo' => 4,], ['foo' => 2,], ['foo' => 1,]],
            $collection2->getArrayCopy()
        );
    }

    /**
     * Объединяет элементы текущей коллекции с элементами переданной и возвращает новую коллекцию
     */
    public function testMerge(): void
    {
        $collection1 = new Collection('array');
        $collection1->add(['foo' => 1,]);
        $collection2 = new Collection('array');
        $collection2->add(['foo' => 2,]);
        $this->assertEquals(
            [['foo' => 1,], ['foo' => 2,]],
            $collection1->merge($collection2)->getArrayCopy()
        );
        $this->assertEquals(
            [['foo' => 1,]],
            $collection1->getArrayCopy()
        );
        $this->assertEquals(
            [['foo' => 2,]],
            $collection2->getArrayCopy()
        );
    }

    /**
     * Сбросить ключи коллекции
     */
    public function testResetKeys(): void
    {
        $collection = new Collection('array');
        $collection->set(1, ['foo' => 1,]);
        $collection->set(2, ['foo' => 2,]);
        $this->assertEquals([0 => ['foo' => 1,], 1 => ['foo' => 2,]], $collection->resetKeys()->getArrayCopy());
    }

    /**
     * Итеративно уменьшает коллекцию к единственному значению, используя callback-функцию
     */
    public function testReduce(): void
    {
        $collection = new Collection('int', [1, 2, 3]);
        $this->assertEquals(6, $collection->reduce(function (?int $sum, int $value) {
            $sum += $value;

            return $sum;
        }));
    }
}
