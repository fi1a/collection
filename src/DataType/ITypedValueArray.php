<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

/**
 * Объект-массив с типизацией значений
 */
interface ITypedValueArray extends IArrayObject
{
    /**
     * Возвращает объявленный тип значений
     */
    public function getType(): string;
}
