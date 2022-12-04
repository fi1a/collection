<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

/**
 * Объект-массив с типизацией значений
 */
interface ITypedValueArray extends ArrayObjectInterface
{
    /**
     * Возвращает объявленный тип значений
     */
    public function getType(): string;
}
