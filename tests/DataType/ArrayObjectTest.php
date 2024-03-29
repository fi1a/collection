<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection\DataType;

use Fi1a\Collection\DataType\ArrayObject;
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
        $array['fields'] = [];
        $this->assertIsArray($array['fields']);
        $array['fields'][] = 1;
        $array['fields'][] = 2;
        $array['fields'][] = 3;
        $this->assertEquals([1, 2, 3], $array['fields']);
        $this->assertNull($array[null]);
        unset($array[null]);
        $this->assertNull($array[null]);
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
     * Тестирование метода count массива
     */
    public function testCount(): void
    {
        $array = new ArrayObject();
        $this->assertEquals(0, $array->count());
        $array[] = 1;
        $array[] = 2;
        $array[] = 3;
        $this->assertEquals(3, $array->count());
    }
}
