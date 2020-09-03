<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection\Fixtures;

use Fi1a\Collection\DataType\TArrayObject;
use Fi1a\Collection\ICollection;
use Fi1a\Collection\TCollection;

/**
 * Для тестирование коллекции
 */
class FixtureCollection implements ICollection
{
    use TArrayObject;
    use TCollection;
}
