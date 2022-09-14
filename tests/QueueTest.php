<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection;

use Fi1a\Collection\Exception\EmptyQueueException;
use Fi1a\Collection\Queue;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование очереди Queue
 */
class QueueTest extends TestCase
{
    /**
     * Вызов методов элементов
     */
    public function testConstructor(): void
    {
        $array = [1, 2, 3];
        $queue = new Queue($array);
        $this->assertFalse($queue->isEmpty());
        $this->assertEquals($array, $queue->getArrayCopy());
    }

    /**
     * Добавить в очередь как первый элемент
     */
    public function testAddBegin(): void
    {
        $queue = new Queue();
        $this->assertTrue($queue->addBegin(1));
        $this->assertTrue($queue->addBegin(2));
        $this->assertTrue($queue->addBegin(3));
        $this->assertEquals(3, $queue[0]);
        $this->assertEquals(2, $queue[1]);
        $this->assertEquals(1, $queue[2]);
    }

    /**
     * Добавить в очередь как последний элемент
     */
    public function testAddEnd(): void
    {
        $queue = new Queue();
        $this->assertTrue($queue->addEnd(1));
        $this->assertTrue($queue->addEnd(2));
        $this->assertTrue($queue->addEnd(3));
        $this->assertEquals(1, $queue[0]);
        $this->assertEquals(2, $queue[1]);
        $this->assertEquals(3, $queue[2]);
    }

    /**
     * Возвращет и удаляет элемент из начала очереди
     */
    public function testPollBegin(): void
    {
        $array = [1, 2, 3];
        $queue = new Queue($array);
        $this->assertEquals(1, $queue->pollBegin());
        $this->assertEquals(2, $queue->pollBegin());
        $this->assertEquals(3, $queue->pollBegin());
        $this->assertNull($queue->pollBegin());
    }

    /**
     * Возвращет и удаляет элемент из конца очереди
     */
    public function testPollEnd(): void
    {
        $array = [1, 2, 3];
        $queue = new Queue($array);
        $this->assertEquals(3, $queue->pollEnd());
        $this->assertEquals(2, $queue->pollEnd());
        $this->assertEquals(1, $queue->pollEnd());
        $this->assertNull($queue->pollEnd());
    }

    /**
     * Возвращете и удаляет элемент из начала очереди.
     * Выбрасывает исключение, если очередь пуста.
     */
    public function testRemoveBegin(): void
    {
        $array = [1, 2, 3];
        $queue = new Queue($array);
        $this->assertEquals(1, $queue->removeBegin());
        $this->assertEquals(2, $queue->removeBegin());
        $this->assertEquals(3, $queue->removeBegin());
        $this->expectException(EmptyQueueException::class);
        $queue->removeBegin();
    }

    /**
     * Возвращете и удаляет элемент из конца очереди.
     * Выбрасывает исключение, если очередь пуста.
     */
    public function testRemoveEnd(): void
    {
        $array = [1, 2, 3];
        $queue = new Queue($array);
        $this->assertEquals(3, $queue->removeEnd());
        $this->assertEquals(2, $queue->removeEnd());
        $this->assertEquals(1, $queue->removeEnd());
        $this->expectException(EmptyQueueException::class);
        $queue->removeEnd();
    }

    /**
     * Возвращает, но не удаляет элемент из начала очереди
     */
    public function testPeekBegin(): void
    {
        $queue = new Queue();
        $this->assertNull($queue->peekBegin());
        $array = [1, 2, 3];
        $queue->exchangeArray($array);
        $this->assertEquals(1, $queue->peekBegin());
        $this->assertEquals(1, $queue->peekBegin());
    }

    /**
     * Возвращает, но не удаляет элемент из конца очереди
     */
    public function testPeekEnd(): void
    {
        $queue = new Queue();
        $this->assertNull($queue->peekEnd());
        $array = [1, 2, 3];
        $queue->exchangeArray($array);
        $this->assertEquals(3, $queue->peekEnd());
        $this->assertEquals(3, $queue->peekEnd());
    }

    /**
     * Возвращает, но не удаляет элемент из начала очереди.
     * Выбрасывает исключение, если очередь пуста.
     */
    public function testBeginElement(): void
    {
        $queue = new Queue([1, 2, 3]);
        $this->assertEquals(1, $queue->beginElement());
        $this->assertEquals(1, $queue->beginElement());

        $queue2 = new Queue();
        $this->expectException(EmptyQueueException::class);
        $queue2->beginElement();
    }

    /**
     * Возвращает, но не удаляет элемент из конца очереди.
     * Выбрасывает исключение, если очередь пуста.
     */
    public function testEndElement(): void
    {
        $queue = new Queue([1, 2, 3]);
        $this->assertEquals(3, $queue->endElement());
        $this->assertEquals(3, $queue->endElement());

        $queue2 = new Queue();
        $this->expectException(EmptyQueueException::class);
        $queue2->endElement();
    }

    /**
     * Добавляем в конец очереди, берем с начала
     */
    public function testLogicBegin(): void
    {
        $queue = new Queue();
        $this->assertTrue($queue->addBegin(1));
        $this->assertTrue($queue->addBegin(2));
        $this->assertEquals(1, $queue->pollEnd());
        $this->assertEquals(2, $queue->pollEnd());
        $this->assertNull($queue->pollEnd());
        $this->assertTrue($queue->addBegin(3));
        $this->assertEquals(3, $queue->pollEnd());
        $this->assertNull($queue->pollEnd());
        $this->assertTrue($queue->addBegin(4));
        $this->assertTrue($queue->addBegin(5));
        $this->assertEquals(4, $queue->pollEnd());
        $this->assertEquals(5, $queue->pollEnd());
    }

    /**
     * Добавляем в начало очереди, берем с конца
     */
    public function testLogicEnd(): void
    {
        $queue = new Queue();
        $this->assertTrue($queue->addEnd(1));
        $this->assertTrue($queue->addEnd(2));
        $this->assertEquals(1, $queue->pollBegin());
        $this->assertEquals(2, $queue->pollBegin());
        $this->assertNull($queue->pollBegin());
        $this->assertTrue($queue->addEnd(3));
        $this->assertEquals(3, $queue->pollBegin());
        $this->assertNull($queue->pollBegin());
        $this->assertTrue($queue->addEnd(4));
        $this->assertTrue($queue->addEnd(5));
        $this->assertEquals(4, $queue->pollBegin());
        $this->assertEquals(5, $queue->pollBegin());
    }
}
