<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\DataType\ArrayObject;
use Fi1a\Collection\DataType\ArrayObjectInterface;

/**
 * Коллекция экземпляров классов ArrayObject
 *
 * @mixin ArrayObjectInterface
 */
class ArrayObjectCollection extends AInstanceCollection
{
    /**
     * @inheritDoc
     */
    protected function factory($key, $value)
    {
        if (!is_array($value)) {
            return false;
        }

        return new ArrayObject($value);
    }

    /**
     * @inheritDoc
     */
    protected function isInstance($value): bool
    {
        return $value instanceof ArrayObjectInterface;
    }
}
