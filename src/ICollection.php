<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\DataType\IMapArrayObject;

/**
 * Интерфейс коллекции
 */
interface ICollection extends IMapArrayObject
{
    /**
     * Возвращает объявленный тип значений
     */
    public function getType(): string;
}
