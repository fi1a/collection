<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection\DataType;

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
    public function testConstruct()
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
    public function testArray()
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
    public function testSetState()
    {
        $input = static::$input;
        $array = ValueObject::__set_state($input);
        $input['key3'] = null;
        $this->assertEquals($array->toArray(), $input);
    }

    /**
     * Тестирование метода клонирования
     */
    public function testGetClone()
    {
        $input = static::$input;
        $array = new ValueObject();
        $array->fromArray($input);
        $this->assertCount(3, $array);
        $clone = $array->getClone();
        $this->assertCount(3, $clone);
        $clone['key4'] = 4;
        $this->assertCount(3, $array);
        $this->assertCount(4, $clone);
    }
}
