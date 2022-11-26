<?php

declare(strict_types=1);

namespace Fi1a\Collection\Helpers;

use Fi1a\Collection\DataType\Exception\OutOfBoundsException;

/**
 * Хелпер с методами для массива
 */
class ArrayHelper
{
    /**
     * Определяет пустой массив или нет
     *
     * @param mixed[] $array
     */
    public static function isEmpty(array $array): bool
    {
        return count($array) === 0;
    }

    /**
     * Возвращает первый элемент
     *
     * @param mixed[] $array
     *
     * @return mixed
     */
    public static function first(array $array)
    {
        if (static::isEmpty($array)) {
            throw new OutOfBoundsException('Can\'t determine first item. Array is empty');
        }
        reset($array);

        return current($array);
    }

    /**
     * Возвращает последний элемент
     *
     * @param mixed[] $array
     *
     * @return mixed
     */
    public static function last(array $array)
    {
        if (static::isEmpty($array)) {
            throw new OutOfBoundsException('Can\'t determine last item. Array is empty');
        }
        /**
         * @var mixed $value
         */
        $value = end($array);
        reset($array);

        return $value;
    }

    /**
     * Проверяет, присутствует ли в массиве указанное значение
     *
     * @param mixed[] $array
     * @param mixed $value
     */
    public static function hasValue(array $array, $value): bool
    {
        return in_array($value, $array, true);
    }

    /**
     * Возвращает ключи массива
     *
     * @param mixed[] $array
     *
     * @return mixed[]
     */
    public static function keys(array $array): array
    {
        return array_keys($array);
    }
}
