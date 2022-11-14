<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection\DataType;

use ErrorException;
use Fi1a\Unit\Collection\DataType\Fixtures\ValueObject;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование класса модели вызова set & get методов
 */
class ValueObjectTest extends TestCase
{
    /**
     * @var array
     */
    protected static $input = [
        'key1' => 1,
        'key2' => 'val2',
    ];

    /**
     * Конструктор
     */
    public function testConstruct(): void
    {
        $input = static::$input;
        $array = new ValueObject($input);
        $input['key1']++;
        $input['key3'] = null;
        $this->assertEquals($array->getArrayCopy(), $input);
    }

    /**
     * Методы инициализации из массива и т.д.
     */
    public function testArray(): void
    {
        $input = static::$input;
        $array = new ValueObject();
        $array->fromArray($input);
        $input['key3'] = null;
        $this->assertEquals($array->toArray(), $input);
        $input['key1']++;
        $this->assertEquals($array->getArrayCopy(), $input);
    }

    /**
     * Восстановление состояния
     */
    public function testSetState(): void
    {
        $input = static::$input;
        $array = ValueObject::__set_state($input);
        $input['key3'] = null;
        $this->assertEquals($array->toArray(), $input);
    }

    /**
     * Тестирование метода клонирования
     */
    public function testGetClone(): void
    {
        $input = static::$input;
        $array = new ValueObject();
        $array->fromArray($input);
        $this->assertCount(3, $array);
        $clone = clone $array;
        $this->assertCount(3, $clone);
        $clone['key4'] = 4;
        $this->assertCount(3, $array);
        $this->assertCount(4, $clone);
    }

    /**
     * Тестирование set & get методов
     */
    public function testSetGetMethods(): void
    {
        $array = new ValueObject();
        $array['fields'] = [];
        $this->assertIsArray($array['fields']);
        $array['fields'][] = 1;
        $array['fields'][] = 2;
        $array['fields'][] = 3;
        $this->assertEquals([1, 2, 3], $array['fields']);
    }

    /**
     * Тестирование __call
     */
    public function testCall(): void
    {
        $array = new ValueObject();
        $array->setKey2(2);
        $this->assertEquals(2, $array->getKey2());
    }

    /**
     * Тестирование __call
     */
    public function testCallException(): void
    {
        $this->expectException(ErrorException::class);
        $array = new ValueObject();
        $array->unknown();
    }

    /**
     * Доступ к свойствам класса
     */
    public function testProperties(): void
    {
        $array = new ValueObject();
        $array->key2 = 2;
        $this->assertEquals(2, $array->key2);
    }
}
