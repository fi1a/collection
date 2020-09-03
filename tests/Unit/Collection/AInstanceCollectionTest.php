<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection;

use Fi1a\Unit\Collection\Fixtures\FixtureClass1;
use Fi1a\Unit\Collection\Fixtures\FixtureInstanceCollection;
use PHPUnit\Framework\TestCase;

/**
 * Тестироввние методов коллекции
 */
class AInstanceCollectionTest extends TestCase
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
        $this->assertTrue($collection->delete(0));
        $this->assertFalse($collection->delete(0));
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
}
