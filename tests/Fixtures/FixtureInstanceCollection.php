<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection\Fixtures;

use Fi1a\Collection\AbstractInstanceCollection;

/**
 * Для тестирования коллекции экземпляров классов
 */
class FixtureInstanceCollection extends AbstractInstanceCollection
{
    /**
     * @inheritDoc
     */
    protected function factory($key, $value)
    {
        return $key % 2 === 0 ? new FixtureClass1($value) : new FixtureClass2($value);
    }

    /**
     * @inheritDoc
     */
    protected function isInstance($value): bool
    {
        return $value instanceof FixtureClass1 || $value instanceof FixtureClass2;
    }
}
