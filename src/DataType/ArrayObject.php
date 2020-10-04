<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

use ArrayIterator;
use Fi1a\Collection\DataType\Exception\OutOfBoundsException;

/**
 * Класс ArrayObject
 */
class ArrayObject extends \ArrayObject implements IArrayObject
{
    /**
     * @inheritDoc
     */
    public function __construct(?array $input = null, int $flags = 0, $iteratorClass = ArrayIterator::class)
    {
        parent::__construct((array) $input, $flags, $iteratorClass);
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(): bool
    {
        return count($this) === 0;
    }

    /**
     * Возвращает первый элемент массива
     *
     * @return mixed
     */
    public function first()
    {
        if ($this->isEmpty()) {
            throw new OutOfBoundsException('Can\'t determine first item. ArrayObject is empty');
        }
        $iterator = new ArrayIterator($this->getArrayCopy());
        $iterator->rewind();

        return $iterator->current();
    }
}
