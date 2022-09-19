<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\DataType\IArrayObject;

/**
 * Интерфейс коллекции
 */
interface ICollection extends IArrayObject
{
    /**
     * Конструктор
     *
     * @param mixed[]|null $data
     */
    public function __construct(string $type = 'mixed', ?array $data = null);

    /**
     * Возвращает объявленный тип значений
     */
    public function getType(): string;
}
