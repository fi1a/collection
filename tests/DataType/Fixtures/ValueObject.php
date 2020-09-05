<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection\DataType\Fixtures;

/**
 * Класс для теста ValueObjectTest
 */
class ValueObject extends \Fi1a\Collection\DataType\ValueObject
{
    /**
     * @var array
     */
    protected $modelKeys = ['key1', 'key2', 'key3'];

    /**
     * Getter для ключа key1
     *
     * @return int
     */
    protected function getKey1()
    {
        return $this->modelGet('key1') - 1;
    }

    /**
     * Setter для ключа key1
     */
    protected function setKey1(?int $value)
    {
        $this->modelSet('key1', $value + 1);
    }
}
