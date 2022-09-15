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
        $array['fields'] = [];
        $this->assertIsArray($array['fields']);
        $array['fields'][] = 1;
        $array['fields'][] = 2;
        $array['fields'][] = 3;
        $this->assertEquals([1, 2, 3], $array['fields']);
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

    /**
     * Очистить массив значений
     */
    public function testClear(): void
    {
        $array = new ArrayObject([1, 2, 3]);
        $this->assertEquals(3, $array->count());
        $array->clear();
        $this->assertEquals(0, $array->count());
        $this->assertEquals([], $array->getArrayCopy());
    }

    /**
     * Проверяет, присутствует ли в массиве указанное значение
     */
    public function testHasValue(): void
    {
        $array = new ArrayObject(['key1' => 1, 'key2' => 2, 'key3' => 3]);
        $this->assertTrue($array->hasValue(2));
        $this->assertFalse($array->hasValue(4));
        $this->assertFalse($array->hasValue(null));
    }

    /**
     * Проверяет, присутствует ли в массиве указанный ключ или индекс
     */
    public function testHas(): void
    {
        $array = new ArrayObject(['key1' => 1, 'key2' => 2, 'key3' => 3]);
        $this->assertTrue($array->has('key1'));
        $this->assertFalse($array->has('key4'));
        $this->assertFalse($array->has(null));
    }

    /**
     * Возвращает ключи массива
     */
    public function testKeys(): void
    {
        $array = new ArrayObject(['key1' => 1, 'key2' => 2, 'key3' => 3]);
        $this->assertEquals(['key1', 'key2', 'key3'], $array->keys());
    }

    /**
     * Вовзвращает значение по ключу, если значения в массиве нет, то возвращает значение $default
     */
    public function testGet(): void
    {
        $array = new ArrayObject(['key1' => 1, 'key2' => 2, 'key3' => 3]);
        $this->assertEquals(1, $array->get('key1'));
        $this->assertNull($array->get('key4'));
        $this->assertEquals(4, $array->get('key4', 4));
        $this->assertNull($array->get(null));
    }

    /**
     * Устанавливает значение по ключу, если значение уже есть в массиве, возвращает его
     */
    public function testPut(): void
    {
        $array = new ArrayObject(['key1' => 1, 'key2' => 2, 'key3' => 3]);
        $this->assertNull($array->put('key4', 4));
        $this->assertEquals(4, $array->put('key4', 5));
        $this->assertEquals(5, $array->get('key4'));
        $this->assertNull($array->put(null, 6));
        $this->assertEquals(6, $array->put(null, 7));
        $this->assertEquals(7, $array->get(null));
    }

    /**
     * Устанавливает значение по ключу, если его нет. Возвращает предыдущее значение
     */
    public function testPutIfAbsent(): void
    {
        $array = new ArrayObject(['key1' => 1, 'key2' => 2, 'key3' => 3]);
        $this->assertNull($array->putIfAbsent('key4', 4));
        $this->assertEquals(4, $array->putIfAbsent('key4', 5));
        $this->assertEquals(4, $array->get('key4'));
        $this->assertNull($array->putIfAbsent(null, 5));
        $this->assertEquals(5, $array->putIfAbsent(null, 5));
    }

    /**
     * Удаляет элемент по ключу, возвращает удаленное значение
     */
    public function testDelete(): void
    {
        $array = new ArrayObject(['key1' => 1, 'key2' => 2, 'key3' => 3, null => 5,]);
        $this->assertNull($array->delete('key4'));
        $this->assertEquals(1, $array->delete('key1'));
        $this->assertNull($array->delete('key1'));
        $this->assertEquals(5, $array->delete(null));
        $this->assertNull($array->delete(null));
    }

    /**
     * Удаляет элемент по ключу, если значение равно переданному. Если элемент удален, возвращает true.
     */
    public function testDeleteIf(): void
    {
        $array = new ArrayObject(['key1' => 1, 'key2' => 2, 'key3' => 3, null => 5,]);
        $this->assertTrue($array->deleteIf('key1', 1));
        $this->assertFalse($array->deleteIf('key1', 1));
        $this->assertFalse($array->deleteIf('key2', 1));
        $this->assertTrue($array->deleteIf(null, 5));
        $this->assertFalse($array->deleteIf(null, 5));
    }

    /**
     * Заменяет значение элемента по ключу, только если есть значение. Возвращает предыдущее значение
     */
    public function testReplace(): void
    {
        $array = new ArrayObject(['key1' => 1, 'key2' => 2, 'key3' => 3, null => 5,]);
        $this->assertEquals(1, $array->replace('key1', 4));
        $this->assertEquals(4, $array->get('key1'));
        $this->assertNull($array->replace('key4', 4));
        $this->assertNull($array->get('key4'));
        $this->assertEquals(5, $array->replace(null, 6));
    }

    /**
     * Заменяет значение элемента по ключу, только если текущее значение равно $oldValue.
     * Если элемент заменен, возвращает true.
     */
    public function testReplaceIf(): void
    {
        $array = new ArrayObject(['key1' => 1, 'key2' => 2, 'key3' => 3, null => 5]);
        $this->assertTrue($array->replaceIf('key1', 1, 4));
        $this->assertFalse($array->replaceIf('key1', 1, 4));
        $this->assertTrue($array->replaceIf(null, 5, 6));
        $this->assertFalse($array->replaceIf(null, 5, 6));
    }
}
