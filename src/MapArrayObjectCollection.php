<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\DataType\IMapArrayObject;
use Fi1a\Collection\DataType\MapArrayObject;

/**
 * Коллекция экземпляров классов ArrayObject
 *
 * @mixin IMapArrayObject
 */
class MapArrayObjectCollection extends AInstanceCollection
{
    /**
     * @inheritDoc
     */
    public static function factory($key, $value)
    {
        if (!is_array($value)) {
            return false;
        }

        return new MapArrayObject($value);
    }

    /**
     * @inheritDoc
     */
    public static function isInstance($value): bool
    {
        return $value instanceof IMapArrayObject;
    }
}
