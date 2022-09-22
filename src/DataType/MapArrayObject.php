<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

/**
 * Объект-массив
 */
class MapArrayObject extends ArrayObject implements IMapArrayObject
{
    use TMapArrayObject;
}
