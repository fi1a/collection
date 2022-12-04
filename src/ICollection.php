<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\DataType\MapArrayObjectInterface;

/**
 * Интерфейс коллекции
 */
interface ICollection extends MapArrayObjectInterface
{
    /**
     * Возвращает объявленный тип значений
     */
    public function getType(): string;
}
