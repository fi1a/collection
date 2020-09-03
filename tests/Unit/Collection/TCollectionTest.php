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
    public function testConstruct()
    {
        $collection = new FixtureCollection();
        $collection->set(0, 1);
        $collection->set(3, 3);
        $collection->set(4, 4);
        $this->assertTrue($collection->has(0));
        $this->assertEquals(1, $collection->get(0));
        $this->assertEquals(2, $collection->get(1, 2));
        $this->assertTrue($collection->delete(0));
        $this->assertFalse($collection->delete(0));
        $this->assertFalse($collection->has(0));
        $counter = 0;
        $collection->each(function ($index, $element) use (&$counter) {
            $counter++;
        });
        $this->assertEquals(2, $counter);
        $collection->map(function ($index, $element) {
            return $element + 1;
        });
        $this->assertEquals([3 => 4, 4 => 5,], $collection->getArrayCopy());
    }
}
