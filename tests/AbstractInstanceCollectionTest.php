<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection;

use Fi1a\Unit\Collection\Fixtures\FixtureClass1;
use Fi1a\Unit\Collection\Fixtures\FixtureInstanceCollection;
use PHPUnit\Framework\TestCase;

/**
 * Тестироввние методов коллекции
 */
class AbstractInstanceCollectionTest extends TestCase
{
    /**
     * Тестирование методов
     */
    public function testConstruct(): void
    {
        $collection = new FixtureInstanceCollection();
        $collection->set(0, 1);
        $collection->set(3, 3);
        $collection->set(4, 4);
        $this->assertTrue($collection->has(0));
        $this->assertInstanceOf(FixtureClass1::class, $collection->get(0));
        $this->assertEquals(1, $collection->get(0)->value);
        $collection->delete(0);
        $this->assertFalse($collection->has(0));
        $collection->delete(0);
        $this->assertFalse($collection->has(0));
        $counter = 0;
        $collection->each(function ($index, $element) use (&$counter) {
            $counter++;
        });
        $this->assertEquals(2, $counter);
    }

    /**
     * Тестирование клонирования
     */
    public function testClone(): void
    {
        $collection = new FixtureInstanceCollection([1, 3, 4]);
        $clone = $collection->getClone();

        $this->assertTrue($clone->has(0));
        $this->assertInstanceOf(FixtureClass1::class, $clone->get(0));
        $this->assertEquals(1, $clone->get(0)->value);
        $this->assertNotEquals($clone->get(0), $collection->has(0));
    }

    /**
     * Тестирование вызова
     */
    public function testCall(): void
    {
        $array = [1, 3, 4];
        $collection = new FixtureInstanceCollection($array);
        $this->assertEquals([1, null, 4], $collection->getValue());
    }

    /**
     * Вычисление расхождения коллекциц с классами
     */
    public function testDiff(): void
    {
        $value1 = new FixtureClass1(1);
        $value2 = new FixtureClass1(2);
        $value3 = new FixtureClass1(3);
        $value4 = new FixtureClass1(4);
        $collection1 = new FixtureInstanceCollection();
        $collection1->add($value1);
        $collection1->add($value2);
        $collection1->add($value3);
        $collection2 = new FixtureInstanceCollection();
        $collection2->add($value4);
        $collection2->add($value2);
        $collection2->add($value1);
        $this->assertEquals([$value3, $value4], $collection1->diff($collection2)->getArrayCopy());
    }

    /**
     * Вычисление расхождения коллекциц с классами
     */
    public function testIntersect(): void
    {
        $value1 = new FixtureClass1(1);
        $value2 = new FixtureClass1(2);
        $value3 = new FixtureClass1(3);
        $value4 = new FixtureClass1(4);
        $collection1 = new FixtureInstanceCollection();
        $collection1->add($value1);
        $collection1->add($value2);
        $collection1->add($value3);
        $collection2 = new FixtureInstanceCollection();
        $collection2->add($value4);
        $collection2->add($value2);
        $collection2->add($value1);
        $this->assertEquals([$value1, $value2], $collection1->intersect($collection2)->getArrayCopy());
    }
}
