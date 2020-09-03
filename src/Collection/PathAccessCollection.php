<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\DataType\IPathAccess;
use Fi1a\Collection\DataType\PathAccess;

/**
 * Коллекция экземпляров классов PathAccess
 */
class PathAccessCollection extends AInstanceCollection
{
    /**
     * @inheritDoc
     */
    public static function factory($key, $value)
    {
        if (!is_array($value)) {
            return false;
        }

        return new PathAccess($value);
    }

    /**
     * @inheritDoc
     */
    public static function isInstance($value): bool
    {
        return $value instanceof IPathAccess;
    }
}
