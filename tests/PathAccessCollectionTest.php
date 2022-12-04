<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection;

use Fi1a\Collection\DataType\IPathAccess;
use Fi1a\Collection\DataType\PathAccess;
use Fi1a\Collection\PathAccessCollection;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование коллекции PathAccessCollection
 */
class PathAccessCollectionTest extends TestCase
{
    /**
     * Вызов методов элементов
     */
    public function testConstructor(): void
    {
        $collection = new PathAccessCollection([
            ['key1' => 1, 'key2' => 'string', 'key3' => [1, 2, 3,]],
            null,
            1,
            [],
            'string',
        ]);
        foreach ($collection as $array) {
            $this->assertTrue($array === false || $array instanceof IPathAccess);
        }
    }

    /**
     * Вызов сеттера
     */
    public function testSetAndCall(): void
    {
        /**
         * @var $collection PathAccessCollection
         */
        $collection = new PathAccessCollection();
        $collection[] = ['key1' => 1, 'key2' => null,];
        $collection[] = 'key1';
        $collection[] = new PathAccess(['key3']);
        $collection[] = ['key1' => 2, 'key2' => null,];
        $this->assertCount(4, $collection);
        $this->assertEquals([1, false, false, 2,], $collection->__call('get', ['key1',]));
    }
}
