<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection\DataType;

use Fi1a\Collection\DataType\TypedValueArray;
use Fi1a\Collection\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование очереди с типизацией значений
 */
class TypedValueArrayTest extends TestCase
{
    /**
     * Тип значения объекта-массива
     */
    public function testConstructor(): void
    {
        $queue = new TypedValueArray('boolean', [true, false]);
        $this->assertEquals('boolean', $queue->getType());
    }

    /**
     * Устанавливает значение
     */
    public function testOffsetSet(): void
    {
        $queue = new TypedValueArray('boolean');
        $queue[] = true;
        $this->assertCount(1, $queue);
        $queue[] = false;
        $this->assertCount(2, $queue);
        $this->expectException(InvalidArgumentException::class);
        $queue[] = 10;
    }
}
