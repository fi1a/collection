<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection\DataType;

use Fi1a\Collection\DataType\Exception\OutOfBoundsException;
use Fi1a\Collection\DataType\IMapArrayObject;
use Fi1a\Collection\DataType\MapArrayObject;
use Fi1a\Collection\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Объект как массив
 */
class MapArrayObjectTest extends TestCase
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
        $this->assertEquals($equal, (new MapArrayObject($data))->getArrayCopy());
    }

    /**
     * Доступ к объекту как к массиву
     */
    public function testOffset(): void
    {
        $array = new MapArrayObject();
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
        $array->set(null, 4);
        $this->assertEquals(4, $array[null]);
        unset($array[null]);
        $this->assertNull($array[null]);
    }

    /**
     * Тестирование клонирования объекта
     */
    public function testClone(): void
    {
        $array = new MapArrayObject();
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
        $array = new MapArrayObject();
        $this->assertTrue($array->isEmpty());
        $array[] = 1;
        $this->assertFalse($array->isEmpty());
    }

    /**
     * Тестирование метода first
     */
    public function testFirst(): void
    {
        $array = new MapArrayObject([1, 2, 3]);
        $this->assertEquals(1, $array->first());
    }

    /**
     * Тестирование метода first (исключение при пустых данных)
     */
    public function testFirstException(): void
    {
        $array = new MapArrayObject();
        $this->expectException(OutOfBoundsException::class);
        $array->first();
    }

    /**
     * Тестирование метода last
     */
    public function testLast(): void
    {
        $array = new MapArrayObject([1, 2, 3]);
        $this->assertEquals(3, $array->last());
    }

    /**
     * Тестирование метода first (исключение при пустых данных)
     */
    public function testLastException(): void
    {
        $array = new MapArrayObject();
        $this->expectException(OutOfBoundsException::class);
        $array->last();
    }

    /**
     * Очистить массив значений
     */
    public function testClear(): void
    {
        $array = new MapArrayObject([1, 2, 3]);
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
        $array = new MapArrayObject(['key1' => 1, 'key2' => 2, 'key3' => 3]);
        $this->assertTrue($array->hasValue(2));
        $this->assertFalse($array->hasValue(4));
        $this->assertFalse($array->hasValue(null));
    }

    /**
     * Проверяет, присутствует ли в массиве указанный ключ или индекс
     */
    public function testHas(): void
    {
        $array = new MapArrayObject(['key1' => 1, 'key2' => 2, 'key3' => 3]);
        $this->assertTrue($array->has('key1'));
        $this->assertFalse($array->has('key4'));
        $this->assertFalse($array->has(null));
    }

    /**
     * Возвращает ключи массива
     */
    public function testKeys(): void
    {
        $array = new MapArrayObject(['key1' => 1, 'key2' => 2, 'key3' => 3]);
        $this->assertEquals(['key1', 'key2', 'key3'], $array->keys());
    }

    /**
     * Вовзвращает значение по ключу, если значения в массиве нет, то возвращает значение $default
     */
    public function testGet(): void
    {
        $array = new MapArrayObject(['key1' => 1, 'key2' => 2, 'key3' => 3]);
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
        $array = new MapArrayObject(['key1' => 1, 'key2' => 2, 'key3' => 3]);
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
        $array = new MapArrayObject(['key1' => 1, 'key2' => 2, 'key3' => 3]);
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
        $array = new MapArrayObject(['key1' => 1, 'key2' => 2, 'key3' => 3, null => 5,]);
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
        $array = new MapArrayObject(['key1' => 1, 'key2' => 2, 'key3' => 3, null => 5,]);
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
        $array = new MapArrayObject(['key1' => 1, 'key2' => 2, 'key3' => 3, null => 5,]);
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
        $array = new MapArrayObject(['key1' => 1, 'key2' => 2, 'key3' => 3, null => 5]);
        $this->assertTrue($array->replaceIf('key1', 1, 4));
        $this->assertFalse($array->replaceIf('key1', 1, 4));
        $this->assertTrue($array->replaceIf(null, 5, 6));
        $this->assertFalse($array->replaceIf(null, 5, 6));
    }

    /**
     * Тестирование метода add массива
     */
    public function testAdd(): void
    {
        $array = new MapArrayObject();
        $array->add(1);
        $array->add(2);
        $array->add(3);
        $this->assertEquals([0 => 1, 1 => 2, 2 => 3,], $array->getArrayCopy());
    }

    /**
     * Тестирование метода count массива
     */
    public function testCount(): void
    {
        $array = new MapArrayObject();
        $this->assertEquals(0, $array->count());
        $array->add(1);
        $array->add(2);
        $array->add(3);
        $this->assertEquals(3, $array->count());
    }

    /**
     * Тестирование метода column массива
     */
    public function testColumn(): void
    {
        $array = new MapArrayObject();
        $array->add(['foo' => 1,]);
        $array->add(['foo' => 2,]);
        $array->add(['foo' => 3,]);
        $this->assertEquals([1, 2, 3,], $array->column('foo'));
    }

    /**
     * Тестирование метода sort массива
     */
    public function testSort(): void
    {
        $array = new MapArrayObject();
        $array->add(['foo' => 3,]);
        $array->add(['foo' => 2,]);
        $array->add(['foo' => 1,]);
        $sorted = $array->sort('foo', IMapArrayObject::SORT_ASC);
        $this->assertEquals([1, 2, 3,], $sorted->column('foo'));
        $this->assertEquals([3, 2, 1,], $array->column('foo'));
        $sorted = $array->sort('foo', IMapArrayObject::SORT_DESC);
        $this->assertEquals([3, 2, 1,], $sorted->column('foo'));
        $this->assertEquals([3, 2, 1,], $array->column('foo'));
    }

    /**
     * Исключение при не известном напрмарвлении сортировки
     */
    public function testSortOrderException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $array = new MapArrayObject();
        $array->add(['foo' => 3,]);
        $array->add(['foo' => 2,]);
        $array->add(['foo' => 1,]);
        $array->sort('foo', 'unknown');
    }

    /**
     * Фильтрация значений массива
     */
    public function testFilter(): void
    {
        $array = new MapArrayObject();
        $array->add(['foo' => 3,]);
        $array->add(['foo' => 2,]);
        $array->add(['foo' => 1,]);
        $filtered = $array->filter(function ($item) {
            return $item['foo'] >= 2;
        });
        $this->assertEquals([['foo' => 3,], ['foo' => 2,]], $filtered->getArrayCopy());
        $this->assertEquals([['foo' => 3,], ['foo' => 2,], ['foo' => 1,]], $array->getArrayCopy());
    }

    /**
     * Возвращает массив с элементами у которых значение ключа, свойства или метода равно переданному значению
     */
    public function testWhere(): void
    {
        $array = new MapArrayObject();
        $array->add(['foo' => 3,]);
        $array->add(['foo' => 2,]);
        $array->add(['foo' => 1,]);
        $filtered = $array->where('foo', 2);
        $this->assertEquals([1 => ['foo' => 2,]], $filtered->getArrayCopy());
    }

    /**
     * Вычисление расхождения
     */
    public function testDiff(): void
    {
        $array1 = new MapArrayObject();
        $array1->add(['foo' => 3,]);
        $array1->add(['foo' => 2,]);
        $array1->add(['foo' => 1,]);
        $array2 = new MapArrayObject();
        $array2->add(['foo' => 4,]);
        $array2->add(['foo' => 2,]);
        $array2->add(['foo' => 1,]);
        $this->assertEquals([['foo' => 3,], ['foo' => 4,]], $array1->diff($array2)->getArrayCopy());
        $this->assertEquals([['foo' => 3,], ['foo' => 2,], ['foo' => 1,]], $array1->getArrayCopy());
        $this->assertEquals([['foo' => 4,], ['foo' => 2,], ['foo' => 1,]], $array2->getArrayCopy());
    }

    /**
     * Вычисляет пересечение
     */
    public function testIntersect(): void
    {
        $array1 = new MapArrayObject();
        $array1->add(['foo' => 3,]);
        $array1->add(['foo' => 2,]);
        $array1->add(['foo' => 1,]);
        $array2 = new MapArrayObject();
        $array2->add(['foo' => 4,]);
        $array2->add(['foo' => 2,]);
        $array2->add(['foo' => 1,]);
        $this->assertEquals(
            [1 => ['foo' => 2,], 2 => ['foo' => 1,]],
            $array1->intersect($array2)->getArrayCopy()
        );
        $this->assertEquals(
            [['foo' => 3,], ['foo' => 2,], ['foo' => 1,]],
            $array1->getArrayCopy()
        );
        $this->assertEquals(
            [['foo' => 4,], ['foo' => 2,], ['foo' => 1,]],
            $array2->getArrayCopy()
        );
    }

    /**
     * Объединяет элементы с элементами переданной и возвращает новый массив
     */
    public function testMerge(): void
    {
        $array1 = new MapArrayObject();
        $array1->add(['foo' => 1,]);
        $array2 = new MapArrayObject();
        $array2->add(['foo' => 2,]);
        $this->assertEquals(
            [['foo' => 1,], ['foo' => 2,]],
            $array1->merge($array2)->getArrayCopy()
        );
        $this->assertEquals(
            [['foo' => 1,]],
            $array1->getArrayCopy()
        );
        $this->assertEquals(
            [['foo' => 2,]],
            $array2->getArrayCopy()
        );
    }

    /**
     * Сбросить ключи
     */
    public function testResetKeys(): void
    {
        $array = new MapArrayObject();
        $array->set(1, ['foo' => 1,]);
        $array->set(2, ['foo' => 2,]);
        $this->assertEquals([0 => ['foo' => 1,], 1 => ['foo' => 2,]], $array->resetKeys()->getArrayCopy());
    }

    /**
     * Итеративно уменьшает к единственному значению, используя callback-функцию
     */
    public function testReduce(): void
    {
        $array = new MapArrayObject([1, 2, 3]);
        $this->assertEquals(6, $array->reduce(function (?int $sum, int $value) {
            $sum += $value;

            return $sum;
        }));
    }

    /**
     * Итеративно уменьшает коллекцию к единственному значению  в обратном порядке, используя callback-функцию
     */
    public function testReduceRight(): void
    {
        $array = new MapArrayObject([1, 2, 3]);
        $this->assertEquals('321', $array->reduceRight(function (?string $sum, int $value) {
            $sum .= $value;

            return $sum;
        }));
    }

    /**
     * Оборачивает значения и возвращает новую коллекцию
     */
    public function testWraps(): void
    {
        $array = new MapArrayObject([1, 2, 3]);
        $this->assertEquals(['"1"', '"2"', '"3"'], $array->wraps('"')->getArrayCopy());
        $this->assertEquals(['"1~', '"2~', '"3~'], $array->wraps('"', '~')->getArrayCopy());
    }

    /**
     * Объединяет элементы в строку
     */
    public function testJoin(): void
    {
        $array = new MapArrayObject([1, 2, 3]);
        $this->assertEquals('1, 2, 3', $array->join(', '));
    }

    /**
     * Вставить значения
     */
    public function testInsert(): void
    {
        $array = new MapArrayObject([1, 2, 3]);
        $array->insert(1, [4, 5]);
        $this->assertEquals([1, 4, 5, 2, 3], $array->getArrayCopy());

        $array = new MapArrayObject([1, 2, 3]);
        $array->insert(4, [4, 5]);
        $this->assertEquals([1, 2, 3, 4, 5,], $array->getArrayCopy());

        $array = new MapArrayObject([1, 2, 3]);
        $array->insert(0, [4, 5]);
        $this->assertEquals([4, 5, 1, 2, 3,], $array->getArrayCopy());
    }

    /**
     * Возвращает ключ первого элемента
     */
    public function testFirstKey(): void
    {
        $array = new MapArrayObject([1, 2, 3]);
        $this->assertEquals(0, $array->firstKey());

        $array = new MapArrayObject(['foo' => 'foo', 'bar' => 'bar']);
        $this->assertEquals('foo', $array->firstKey());

        $array = new MapArrayObject([]);
        $this->assertFalse($array->firstKey());
    }

    /**
     * Возвращает ключ последнего элемента
     */
    public function testLastKey(): void
    {
        $array = new MapArrayObject([1, 2, 3]);
        $this->assertEquals(2, $array->lastKey());

        $array = new MapArrayObject(['foo' => 'foo', 'bar' => 'bar']);
        $this->assertEquals('bar', $array->lastKey());

        $array = new MapArrayObject([]);
        $this->assertFalse($array->lastKey());
    }

    /**
     * Переключает значения
     */
    public function testToggle(): void
    {
        $array = new MapArrayObject(['foo' => 'foo']);
        $array->toggle('foo', 'foo', 'bar');
        $this->assertEquals(['foo' => 'bar'], $array->getArrayCopy());
        $array->toggle('foo', 'foo', 'bar');
        $this->assertEquals(['foo' => 'foo'], $array->getArrayCopy());

        $array = new MapArrayObject(['' => 'foo']);
        $array->toggle(null, 'foo', 'bar');
        $this->assertEquals(['' => 'bar'], $array->getArrayCopy());
        $array->toggle(null, 'foo', 'bar');
        $this->assertEquals(['' => 'foo'], $array->getArrayCopy());
    }

    /**
     * Возвращает true, если все элементы удовлетворяют условию
     */
    public function testEvery(): void
    {
        $array = new MapArrayObject([1, 2, 3]);
        $this->assertFalse($array->every(function (int $value, $index) {
            return $value > 2;
        }));

        $array = new MapArrayObject([3, 4, 5]);
        $this->assertTrue($array->every(function (int $value, $index) {
            return $value > 2;
        }));
    }

    /**
     * Удаляет все элементы удовлетворяющие условию
     */
    public function testWithout(): void
    {
        $array = new MapArrayObject([1, 2, 3]);
        $without = $array->without(function (int $value, $index) {
            return $value > 2;
        });

        $this->assertEquals([1], $without->getArrayCopy());
    }
}
