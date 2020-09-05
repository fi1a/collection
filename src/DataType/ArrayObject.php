<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

use ArrayIterator;

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
    public function getClone()
    {
        return new static($this->getArrayCopy());
    }
}
