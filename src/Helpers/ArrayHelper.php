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

    /**
     * Есть ли элемент с таким ключем
     *
     * @param mixed[]           $array
     * @param string|int|null $key
     */
    public static function has(array $array, $key): bool
    {
        if (is_null($key)) {
            $key = (string) $key;
        }

        return array_key_exists($key, $array);
    }

    /**
     * Возвращает элемент по ключу
     *
     * @param mixed[]         $array
     * @param string|int|null $key     ключ
     * @param mixed           $default значение по умолчанию, возвращается при отсутствии ключа
     *
     * @return mixed
     */
    public static function get(array $array, $key, $default = null)
    {
        if (!static::has($array, $key)) {
            return $default;
        }
        if (is_null($key)) {
            $key = (string) $key;
        }

        return $array[$key];
    }

    /**
     * Устанавливает значение по ключу
     *
     * @param mixed[]         $array
     * @param string|int|null $key   ключ
     * @param mixed           $value устанавливаемое значение
     *
     * @return mixed[]
     */
    public static function set(array $array, $key, $value): array
    {
        if (is_null($key)) {
            $key = (string) $key;
        }
        /** @psalm-suppress MixedAssignment */
        $array[$key] = $value;

        return $array;
    }

    /**
     * Удаляет элемент по ключу, возвращает удаленное значение
     *
     * @param mixed[]         $array
     * @param string|int|null $key ключ
     *
     * @return mixed
     */
    public static function delete(array &$array, $key)
    {
        /**
         * @var mixed $prev
         */
        $prev = static::get($array, $key);
        if (static::has($array, $key)) {
            if (is_null($key)) {
                $key = (string) $key;
            }
            unset($array[$key]);
        }

        return $prev;
    }

    /**
     * Удаляет элемент по ключу, если значение равно переданному. Если элемент удален, возвращает true.
     *
     * @param mixed[]         $array
     * @param string|int|null $key ключ
     * @param mixed           $value
     */
    public static function deleteIf(array &$array, $key, $value): bool
    {
        if (static::get($array, $key) === $value) {
            static::delete($array, $key);

            return true;
        }

        return false;
    }

    /**
     * Устанавливает значение по ключу, если значение уже есть в массиве, возвращает его
     *
     * @param mixed[]         $array
     * @param string|int|null $key
     * @param mixed           $value
     *
     * @return mixed
     */
    public static function put(array &$array, $key, $value)
    {
        /**
         * @var mixed $prev
         */
        $prev = static::get($array, $key);
        $array = static::set($array, $key, $value);

        return $prev;
    }

    /**
     * Устанавливает значение по ключу, если его нет. Возвращает предыдущее значение
     *
     * @param mixed[]         $array
     * @param string|int|null $key
     * @param mixed           $value
     *
     * @return mixed
     */
    public static function putIfAbsent(array &$array, $key, $value)
    {
        /**
         * @var mixed $prev
         */
        $prev = static::get($array, $key);
        if (is_null($prev)) {
            $array = static::set($array, $key, $value);
        }

        return $prev;
    }
}
