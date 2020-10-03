<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection;

use Fi1a\Unit\Collection\Fixtures\FixtureCollection;
use PHPUnit\Framework\TestCase;

/**
 * Тестироввние методов коллекции
 */
class TCollectionTest extends TestCase
{
    /**
     * Тестирование методов
     */
    public function testConstruct(): void
    {
        $collection = new FixtureCollection();
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
        $collection = new FixtureCollection();
        $collection->add(1);
        $collection->add(2);
        $collection->add(3);
        $this->assertEquals([0 => 1, 1 => 2, 2 => 3,], $collection->getArrayCopy());
    }
}
