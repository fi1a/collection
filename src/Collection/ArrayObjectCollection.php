<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\DataType\ArrayObject;
use Fi1a\Collection\DataType\IArrayObject;

/**
 * Коллекция экземпляров классов ArrayObject
 */
class ArrayObjectCollection extends AInstanceCollection
{
    /**
     * @inheritDoc
     */
    public static function factory($key, $value)
    {
        if (!is_array($value)) {
            return false;
        }

        return new ArrayObject($value);
    }

    /**
     * @inheritDoc
     */
    public static function isInstance($value): bool
    {
        return $value instanceof IArrayObject;
    }
}
