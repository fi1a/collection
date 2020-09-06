<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection\DataType;

use Fi1a\Collection\DataType\ArrayObject;
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
}
