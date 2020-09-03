<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

use ArrayAccess;
use Countable;
use IteratorAggregate;

/**
 * Интерфейс IArrayObject
 */
interface IArrayObject extends IteratorAggregate, ArrayAccess, Countable
{
    /**
     * Creates a copy of the ArrayObject.
     *
     * @return mixed[]
     */
    public function getArrayCopy();

    /**
     * Exchange the array for another one.
     *
     * @param mixed[] $input массив со значениями для замены.
     *
     * @return void
     */
    public function exchangeArray(array $input);

    /**
     * Клонирование объекта.
     *
     * @return static
     */
    public function getClone();
}
