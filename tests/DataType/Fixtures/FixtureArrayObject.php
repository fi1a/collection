<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection\DataType\Fixtures;

use Fi1a\Collection\DataType\IArrayObject;
use Fi1a\Collection\DataType\TArrayObject;

/**
 * Для тестирования объекта как массива
 */
class FixtureArrayObject implements IArrayObject
{
    use TArrayObject;
}
