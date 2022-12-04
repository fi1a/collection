<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\DataType\MapArrayObject;
use Fi1a\Collection\DataType\MapArrayObjectInterface;

/**
 * Коллекция экземпляров классов ArrayObject
 *
 * @mixin MapArrayObjectInterface
 */
class MapArrayObjectCollection extends AInstanceCollection
{
    /**
     * @inheritDoc
     */
    protected function factory($key, $value)
    {
        if (!is_array($value)) {
            return false;
        }

        return new MapArrayObject($value);
    }

    /**
     * @inheritDoc
     */
    protected function isInstance($value): bool
    {
        return $value instanceof MapArrayObjectInterface;
    }
}
