<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection;

use Fi1a\Collection\Exception\InvalidArgumentException;
use Fi1a\Collection\TypedValueQueue;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование очереди с типизацией значений
 */
class TypedValueQueueTest extends TestCase
{
    /**
     * Тип значения
     */
    public function testConstructor(): void
    {
        $queue = new TypedValueQueue('boolean', [true, false]);
        $this->assertEquals('boolean', $queue->getType());
    }

    /**
     * Проверка типа значения при добавлении элемента
     */
    public function testOffsetSet(): void
    {
        $queue = new TypedValueQueue('boolean');
        $queue[] = true;
        $this->assertCount(1, $queue);
        $queue[] = false;
        $this->assertCount(2, $queue);
        $this->expectException(InvalidArgumentException::class);
        $queue[] = 10;
    }

    /**
     * Проверка типа значения при добавлении элемента в начало очереди
     */
    public function testAddBegin(): void
    {
        $queue = new TypedValueQueue('boolean');
        $this->assertTrue($queue->addBegin(true));
        $this->assertCount(1, $queue);
        $this->assertTrue($queue->addBegin(false));
        $this->assertCount(2, $queue);
        $this->expectException(InvalidArgumentException::class);
        $queue->addBegin(10);
    }

    /**
     * Проверка типа значения при добавлении элемента в конец очереди
     */
    public function testAddEnd(): void
    {
        $queue = new TypedValueQueue('boolean');
        $this->assertTrue($queue->addEnd(true));
        $this->assertCount(1, $queue);
        $this->assertTrue($queue->addEnd(false));
        $this->assertCount(2, $queue);
        $this->expectException(InvalidArgumentException::class);
        $queue->addEnd(10);
    }

    /**
     * Добавить в начало очереди, без исключения при проверке типа значения
     */
    public function testOfferBegin(): void
    {
        $queue = new TypedValueQueue('boolean');
        $this->assertTrue($queue->offerBegin(true));
        $this->assertCount(1, $queue);
        $this->assertTrue($queue->offerBegin(false));
        $this->assertCount(2, $queue);
        $this->assertFalse($queue->offerBegin(10));
    }

    /**
     * Добавить в конец очереди, без исключения при проверке типа значения
     */
    public function testOfferEnd(): void
    {
        $queue = new TypedValueQueue('boolean');
        $this->assertTrue($queue->offerEnd(true));
        $this->assertCount(1, $queue);
        $this->assertTrue($queue->offerEnd(false));
        $this->assertCount(2, $queue);
        $this->assertFalse($queue->offerEnd(10));
    }
}
