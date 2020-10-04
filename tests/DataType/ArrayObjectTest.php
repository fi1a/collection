<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection\DataType;

use Fi1a\Collection\DataType\ArrayObject;
use Fi1a\Collection\DataType\Exception\OutOfBoundsException;
use Fi1a\Collection\DataType\IArrayObject;
use Fi1a\Unit\Collection\DataType\Fixtures\FixtureArrayObject;
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
     * Провайдер данных для теста testOffset
     *
     * @return IArrayObject[]
     */
    public function dataProviderOffset(): array
    {
        return [
            [new ArrayObject()],
            [new FixtureArrayObject()],
        ];
    }

    /**
     * Доступ к объекту как к массиву
     *
     * @dataProvider dataProviderOffset
     */
    public function testOffset(IArrayObject $array): void
    {
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
     * Провайдер данных для теста testClone
     *
     * @return IArrayObject[]
     */
    public function dataProviderClone(): array
    {
        return [
            [new ArrayObject()],
            [new FixtureArrayObject()],
        ];
    }

    /**
     * Тестирование клонирования объекта
     *
     * @dataProvider dataProviderOffset
     */
    public function testClone(IArrayObject $array): void
    {
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
     * Провайдер данных для теста testIsEmpty
     *
     * @return IArrayObject[][]
     */
    public function dataProviderIsEmpty(): array
    {
        return [
            [new ArrayObject()],
            [new FixtureArrayObject()],
        ];
    }

    /**
     * Тестирование метода isEmpty
     *
     * @dataProvider dataProviderIsEmpty
     */
    public function testIsEmpty(IArrayObject $array): void
    {
        $this->assertTrue($array->isEmpty());
        $array[] = 1;
        $this->assertFalse($array->isEmpty());
    }

    /**
     * Провайдер данных для теста testFirst
     *
     * @return IArrayObject[][]
     */
    public function dataProviderFirst(): array
    {
        return [
            [new ArrayObject([1, 2, 3])],
            [new FixtureArrayObject([1, 2, 3])],
        ];
    }

    /**
     * Тестирование метода first
     *
     * @dataProvider dataProviderFirst
     */
    public function testFirst(IArrayObject $array): void
    {
        $this->assertEquals(1, $array->first());
    }

    /**
     * Провайдер данных для теста testFirstException
     *
     * @return IArrayObject[][]
     */
    public function dataProviderFirstException(): array
    {
        return [
            [new ArrayObject()],
            [new FixtureArrayObject()],
        ];
    }

    /**
     * Тестирование метода first (исключение при пустых данных)
     *
     * @dataProvider dataProviderFirstException
     */
    public function testFirstException(IArrayObject $array): void
    {
        $this->expectException(OutOfBoundsException::class);
        $array->first();
    }

    /**
     * Провайдер данных для теста testLast
     *
     * @return IArrayObject[][]
     */
    public function dataProviderLast(): array
    {
        return [
            [new ArrayObject([1, 2, 3])],
            [new FixtureArrayObject([1, 2, 3])],
        ];
    }

    /**
     * Тестирование метода last
     *
     * @dataProvider dataProviderLast
     */
    public function testLast(IArrayObject $array): void
    {
        $this->assertEquals(3, $array->last());
    }

    /**
     * Провайдер данных для теста testLastException
     *
     * @return IArrayObject[][]
     */
    public function dataProviderLastException(): array
    {
        return [
            [new ArrayObject()],
            [new FixtureArrayObject()],
        ];
    }

    /**
     * Тестирование метода first (исключение при пустых данных)
     *
     * @dataProvider dataProviderLastException
     */
    public function testLastException(IArrayObject $array): void
    {
        $this->expectException(OutOfBoundsException::class);
        $array->last();
    }
}
