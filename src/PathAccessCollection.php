<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\DataType\PathAccess;
use Fi1a\Collection\DataType\PathAccessInterface;

/**
 * Коллекция экземпляров классов PathAccess
 *
 * @mixin PathAccess
 */
class PathAccessCollection extends AbstractInstanceCollection
{
    /**
     * @inheritDoc
     */
    protected function factory($key, $value)
    {
        if (!is_array($value)) {
            return false;
        }

        return new PathAccess($value);
    }

    /**
     * @inheritDoc
     */
    protected function isInstance($value): bool
    {
        return $value instanceof PathAccessInterface;
    }
}
