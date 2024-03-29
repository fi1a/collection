<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection;

use Fi1a\Collection\ArrayObjectCollection;
use Fi1a\Collection\DataType\ArrayObject;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование коллекции ArrayObjectCollection
 */
class ArrayObjectCollectionTest extends TestCase
{
    /**
     * Вызов методов элементов
     */
    public function testConstructor(): void
    {
        $collection = new ArrayObjectCollection([
            ['key1' => 1, 'key2' => 'string', 'key3' => [1, 2, 3,]],
            null,
            1,
            [],
            'string',
        ]);
        foreach ($collection as $array) {
            $this->assertTrue($array === false || $collection->isInstance($array));
        }
    }

    /**
     * Вызов сеттера
     */
    public function testSet(): void
    {
        /**
         * @var $collection ArrayObjectCollection
         */
        $collection = new ArrayObjectCollection();
        $collection[] = ['key1' => 1, 'key2' => null,];
        $collection[] = 'key1';
        $collection[] = new ArrayObject(['key3']);
        $collection[] = $collection->factory(2, ['key1' => 2, 'key2' => null,]);
        $this->assertCount(4, $collection);
    }
}
