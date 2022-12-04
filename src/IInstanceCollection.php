<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\DataType\ArrayObjectInterface;

/**
 * Интерфейс коллекции экземпляров классов
 */
interface IInstanceCollection extends ArrayObjectInterface
{
    /**
     * Магический метод
     *
     * Пробрасывает вызов функции для каждого элемента и возвращает массив значений
     * результата выполнения этих методов или null, если такого метода нет
     *
     * @param string $func название функции
     * @param mixed[]  $args аргументы функции
     *
     * @return mixed[]
     */
    public function __call(string $func, array $args);

    /**
     * Возвращает копию объекта
     *
     * @return static
     */
    public function getClone();
}
