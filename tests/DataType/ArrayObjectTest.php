<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection\DataType;

use Fi1a\Collection\DataType\ArrayObject;
use Fi1a\Collection\DataType\Exception\OutOfBoundsException;
use PHPUnit\Framework\TestCase;

/**
 * Объект как массив
 */
class ArrayObjectTest extends TestCase
{
    /**
     * Провайдер данных для теста testConstruct
     *
     * @return mixed[]
     */
    public function dataProviderConstruct(): array
    {
        return [
            [null, []],
            [[], []],
            [[1, 2, 3], [1, 2, 3]],
        ];
    }

    /**
     * Тестирование инициализации
     *
     * @param mixed $data
     * @param mixed $equal
     *
     * @dataProvider dataProviderConstruct
     */
    public function testConstruct($data, $equal): void
    {
        $this->assertEquals($equal, (new ArrayObject($data))->getArrayCopy());
    }

    /**
     * Доступ к объекту как к массиву
     */
    public function testOffset(): void
    {
        $array = new ArrayObject();
        $array[1] = 1;
        $this->assertEquals(1, $array[1]);
        $this->assertEquals([1 => 1], $array->getArrayCopy());
        unset($array[1]);
        $array->exchangeArray([0 => 0]);
        $this->assertEquals([0 => 0], $array->getArrayCopy());
        $this->assertCount(1, $array);
        $this->assertTrue(isset($array[0]));
        foreach ($array as $ind => $value) {
            $this->assertEquals($array[$ind], $value);
        }
        $array[] = 1;
        $this->assertEquals([0 => 0, 1 => 1,], $array->getArrayCopy());
    }

    /**
     * Тестирование клонирования объекта
     */
    public function testClone(): void
    {
        $array = new ArrayObject();
        $array[0] = 1;
        $array[2] = 2;
        $array[3] = 3;
        $this->assertCount(3, $array);
        $clone = clone $array;
        $this->assertCount(3, $clone);
        $clone[0] = 4;
        $this->assertEquals(4, $clone[0]);
        $this->assertEquals(1, $array[0]);
    }

    /**
     * Тестирование метода isEmpty
     */
    public function testIsEmpty(): void
    {
        $array = new ArrayObject();
        $this->assertTrue($array->isEmpty());
        $array[] = 1;
        $this->assertFalse($array->isEmpty());
    }

    /**
     * Тестирование метода first
     */
    public function testFirst(): void
    {
        $array = new ArrayObject([1, 2, 3]);
        $this->assertEquals(1, $array->first());
    }

    /**
     * Тестирование метода first (исключение при пустых данных)
     */
    public function testFirstException(): void
    {
        $array = new ArrayObject();
        $this->expectException(OutOfBoundsException::class);
        $array->first();
    }

    /**
     * Тестирование метода last
     */
    public function testLast(): void
    {
        $array = new ArrayObject([1, 2, 3]);
        $this->assertEquals(3, $array->last());
    }

    /**
     * Тестирование метода first (исключение при пустых данных)
     */
    public function testLastException(): void
    {
        $array = new ArrayObject();
        $this->expectException(OutOfBoundsException::class);
        $array->last();
    }
}
