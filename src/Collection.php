<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\DataType\ArrayObject;

/**
 * Коллекция
 */
class Collection extends ArrayObject implements ICollection
{
    use TCollection;
}
