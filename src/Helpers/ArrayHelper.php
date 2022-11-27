<?php

declare(strict_types=1);

namespace Fi1a\Collection\Helpers;

use Fi1a\Collection\DataType\Exception\OutOfBoundsException;
use Fi1a\Collection\DataType\IArrayObject;
use Fi1a\Collection\Exception\ExtractValueException;
use Fi1a\Collection\Exception\InvalidArgumentException;

/**
 * Хелпер с методами для массива
 */
class ArrayHelper
{
    public const SORT_ASC = 'asc';

    public const SORT_DESC = 'desc';

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
     * @param mixed   $value
     */
    public static function hasValue(array $array, $value, bool $strict = true): bool
    {
        return in_array($value, $array, $strict);
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

    /**
     * Заменяет значение элемента по ключу, только если есть значение. Возвращает предыдущее значение
     *
     * @param mixed[]         $array
     * @param string|int|null $key
     * @param mixed           $value
     *
     * @return mixed
     */
    public static function replace(array &$array, $key, $value)
    {
        /**
         * @var mixed $prev
         */
        $prev = static::get($array, $key);
        if (static::has($array, $key)) {
            $array = static::set($array, $key, $value);
        }

        return $prev;
    }

    /**
     * Заменяет значение элемента по ключу, только если текущее значение равно $oldValue.
     * Если элемент заменен, возвращает true.
     *
     * @param mixed[]         $array
     * @param string|int|null $key
     * @param mixed           $oldValue
     * @param mixed           $newValue
     */
    public static function replaceIf(array &$array, $key, $oldValue, $newValue): bool
    {
        if (static::get($array, $key) === $oldValue) {
            $array = static::set($array, $key, $newValue);

            return true;
        }

        return false;
    }

    /**
     * Вызывает функцию, передавая ключ и значение из коллекции
     *
     * @param mixed[]                     $array
     * @param callable(mixed, mixed):void $callback функция, принимающая ключ и значение из коллекции
     */
    public static function each(array $array, callable $callback): void
    {
        /**
         * @var string|int $index
         * @var mixed $value
         */
        foreach ($array as $index => $value) {
            call_user_func($callback, $value, $index);
        }
    }

    /**
     * Вызывает функцию, передавая ключ и значение из коллекции и заменяет элемент результатом
     *
     * @param mixed[]                        $array
     * @param callable(mixed, mixed):mixed $callback функция, принимающая ключ и значение из коллекции
     *
     * @return mixed[]
     */
    public static function map(array $array, callable $callback): array
    {
        /**
         * @var string|int $index
         * @var mixed $value
         */
        foreach ($array as $index => $value) {
            /**
             * @var mixed
             */
            $array[$index] = call_user_func($callback, $value, $index);
        }

        return $array;
    }

    /**
     * Возвращает значения переданного ключа, свойства или метода
     *
     * @param mixed[]  $array
     * @param string $name ключ, свойство или метод
     *
     * @return mixed[]
     *
     * @throws ExtractValueException
     */
    public static function column(array $array, string $name): array
    {
        $values = [];
        /**
         * @var mixed $value
         */
        foreach ($array as $value) {
            /**
             * @var mixed
             */
            $values[] = static::extractValue($value, $name);
        }

        return $values;
    }

    /**
     * Сортировка элементов коллекции по значениям переданного ключа, свойства или метода
     *
     * @param mixed[]  $array
     * @param string $name  ключ, свойство или метод
     * @param string $order направление сортировки
     *
     * @return mixed[]
     *
     * @throws ExtractValueException
     */
    public static function sort(array $array, string $name, string $order = self::SORT_ASC): array
    {
        if (!in_array($order, [self::SORT_ASC, self::SORT_DESC], true)) {
            throw new InvalidArgumentException('Invalid order: ' . $order);
        }
        $sort = /**
         * @param mixed $a
         * @param mixed $b
         *
         * @return int
         */function ($a, $b) use ($name, $order): int {
            /**
             * @var mixed
             */
            $aValue = static::extractValue($a, $name);
            /**
             * @var mixed
             */
            $bValue = static::extractValue($b, $name);

            return ($aValue <=> $bValue) * ($order === self::SORT_DESC ? -1 : 1);
};
        uasort($array, $sort);

        return $array;
    }

    /**
     * Возвращает отфильтрованную коллекцию
     *
     * @param mixed[]                          $array
     * @param callable(mixed, mixed=):scalar $callback функция для фильтрации
     *
     * @return mixed[]
     */
    public static function filter(array $array, callable $callback): array
    {
        return array_filter($array, $callback);
    }

    /**
     * Возвразает коллекцию с элементами у которых значение ключа, свойства или метода равно переданному значению
     *
     * @param mixed[]  $array
     * @param string $name  ключ, свойство или метод
     * @param mixed  $value значение для сравнения
     *
     * @return mixed[]
     */
    public static function where(array $array, string $name, $value): array
    {
        $filter = /**
         * @param mixed $item
         */function ($item) use ($name, $value): bool {
            return $value === static::extractValue($item, $name);
};

        return static::filter($array, $filter);
    }

    /**
     * Возвращает новую коллекцию с расходящимися элементами текущей коллекции с переданной
     *
     * @param mixed[] $array
     * @param mixed[] $collection
     *
     * @return mixed[]
     */
    public static function diff(array $array, array $collection): array
    {
        $comparator = /**
         * @param mixed $a
         * @param mixed $b
         */function ($a, $b): int {
    if (is_object($a) && is_object($b)) {
        $a = spl_object_id($a);
        $b = spl_object_id($b);
    }

            return $a === $b ? 0 : ($a < $b ? 1 : -1);
};

        $diff1 = array_udiff($array, $collection, $comparator);
        $diff2 = array_udiff($collection, $array, $comparator);

        return array_merge($diff1, $diff2);
    }

    /**
     * Возвращает новую коллекцию с пересечением элементов текущей коллекции с переданной
     *
     * @param mixed[] $array
     * @param mixed[] $collection коллекция для вычисления пересечения
     *
     * @return mixed[]
     */
    public static function intersect(array $array, array $collection): array
    {
        $function = /**
         * @param mixed $a
         * @param mixed $b
         */function ($a, $b): int {
    if (is_object($a) && is_object($b)) {
        $a = spl_object_id($a);
        $b = spl_object_id($b);
    }

            return $a === $b ? 0 : ($a < $b ? 1 : -1);
};

        return array_uintersect($array, $collection, $function);
    }

    /**
     * Сбросить ключи
     *
     * @param mixed[] $array
     *
     * @return mixed[]
     */
    public static function resetKeys(array $array): array
    {
        return array_values($array);
    }

    /**
     * Итеративно уменьшает коллекцию к единственному значению, используя callback-функцию
     *
     * @param mixed[]                      $array
     * @param callable(mixed, mixed):mixed $callback
     * @param mixed                        $initial
     *
     * @return mixed
     */
    public static function reduce(array $array, callable $callback, $initial = null)
    {
        return array_reduce($array, $callback, $initial);
    }

    /**
     * Итеративно уменьшает коллекцию к единственному значению  в обратном порядке, используя callback-функцию
     *
     * @param mixed[]                      $array
     * @param callable(mixed, mixed):mixed $callback
     * @param mixed $initial
     *
     * @return mixed
     */
    public static function reduceRight(array $array, callable $callback, $initial = null)
    {
        return static::reduce(array_reverse($array), $callback, $initial);
    }

    /**
     * Оборачивает значения
     *
     * @param mixed[]     $array
     *
     * @return mixed[]
     */
    public static function wraps(array $array, string $prefix, ?string $suffix = null): array
    {
        if (is_null($suffix)) {
            $suffix = $prefix;
        }

        return static::map($array, function ($value) use ($prefix, $suffix) {
            $value = (string) $value;

            return $prefix . $value . $suffix;
        });
    }

    /**
     * Объединяет элементы в строку
     *
     * @param mixed[]  $array
     */
    public static function join(array $array, string $separator): string
    {
        /** @psalm-suppress MixedArgumentTypeCoercion */
        return implode($separator, $array);
    }

    /**
     * Вставить значения
     *
     * @param mixed[] $array
     * @param mixed[] $values
     *
     * @return mixed[]
     */
    public static function insert(array $array, int $index, array $values): array
    {
        return array_merge(
            array_slice($array, 0, $index),
            $values,
            array_slice($array, $index),
        );
    }

    /**
     * Возвращает ключ первого элемента
     *
     * @param mixed[] $array
     *
     * @return string|int|false
     */
    public static function firstKey(array $array)
    {
        $keys = array_keys($array);
        reset($keys);

        return current($keys);
    }

    /**
     * Возвращает ключ последнего элемента
     *
     * @param mixed[] $array
     *
     * @return string|int|false
     */
    public static function lastKey(array $array)
    {
        $keys = array_keys($array);

        return end($keys);
    }

    /**
     * Переключает значения
     *
     * @param mixed[] $array
     * @param string|int|null $key
     * @param mixed $firstValue
     * @param mixed $secondValue
     *
     * @return mixed[]
     */
    public static function toggle(array $array, $key, $firstValue, $secondValue): array
    {
        if (is_null($key)) {
            $key = (string) $key;
        }
        if ($array[$key] === $firstValue) {
            /**
             * @var mixed
             */
            $array[$key] = $secondValue;

            return $array;
        }
        /**
         * @var mixed
         */
        $array[$key] = $firstValue;

        return $array;
    }

    /**
     * Извлекает значение из массива или объекта
     *
     * @param mixed  $object значение
     * @param string $name   название ключа, свойства или метода
     *
     * @return mixed
     *
     * @throws ExtractValueException
     */
    private static function extractValue($object, string $name)
    {
        if (is_array($object) || $object instanceof IArrayObject) {
            return $object[$name];
        }
        if (is_object($object)) {
            if (property_exists($object, $name)) {
                return $object->$name;
            }
            if (method_exists($object, $name)) {
                return $object->{$name}();
            }
        }

        throw new ExtractValueException(sprintf('Name of column "%s" not found', $name));
    }

    /**
     * Возвращает true, если все элементы удовлетворяют условию
     *
     * @param mixed[]               $array
     * @param callable(mixed, string|int): bool $condition
     */
    public static function every(array $array, callable $condition): bool
    {
        $result = true;

        /** @psalm-suppress MixedAssignment */
        foreach ($array as $index => $value) {
            if ($condition($value, $index) === false) {
                $result = false;

                break;
            }
        }

        return $result;
    }

    /**
     * Возвращает массив без элементов удовлетворяющих условию
     *
     * @param mixed[]                           $array
     * @param callable(mixed, string|int): bool $condition
     *
     * @return mixed[]
     */
    public static function without(array $array, callable $condition): array
    {
        $result = [];
        /** @psalm-suppress MixedAssignment */
        foreach ($array as $index => $value) {
            if ($condition($value, $index) === false) {
                $result[$index] = $value;

                break;
            }
        }

        return $result;
    }

    /**
     * Возвращает массив с элементами удовлетворяющими условию
     *
     * @param mixed[]                           $array
     * @param callable(mixed, string|int): bool $condition
     *
     * @return mixed[]
     */
    public static function with(array $array, callable $condition): array
    {
        $result = [];
        /** @psalm-suppress MixedAssignment */
        foreach ($array as $index => $value) {
            if ($condition($value, $index) === true) {
                $result[$index] = $value;

                break;
            }
        }

        return $result;
    }

    /**
     * Возвращает массив, опуская заданное количество элементов с начала
     *
     * @param mixed[] $array
     *
     * @return mixed[]
     */
    public static function drop(array $array, int $count): array
    {
        if ($count <= 0) {
            throw new InvalidArgumentException('Количество должно быть больше 0');
        }

        return array_slice($array, $count);
    }

    /**
     * Возвращает массив, опуская заданное количество элементов с конца
     *
     * @param mixed[] $array
     *
     * @return mixed[]
     */
    public static function dropRight(array $array, int $count): array
    {
        if ($count <= 0) {
            throw new InvalidArgumentException('Количество должно быть больше 0');
        }

        return array_slice($array, 0, -1 * $count);
    }

    /**
     * Возвращает первый элемент, который удовлетворяет условию $condition,
     * возвращает false, если такого элемента не существует
     *
     * @param mixed[] $array
     * @param callable(mixed, string|int): bool $condition
     *
     * @return mixed
     */
    public static function find(array $array, callable $condition)
    {
        /** @psalm-suppress MixedAssignment */
        foreach ($array as $index => $value) {
            if ($condition($value, $index) === true) {
                return $value;
            }
        }

        return false;
    }

    /**
     * Возвращает последний элемент, который удовлетворяет условию $condition,
     * возвращает false, если такого элемента не существует
     *
     * @param mixed[] $array
     * @param callable(mixed, string|int): bool $condition
     *
     * @return mixed
     */
    public static function findLast(array $array, callable $condition)
    {
        return static::find(array_reverse($array), $condition);
    }

    /**
     * Возвращает первый ключ элемента, который удовлетворяет условию $condition,
     * возвращает false, если такого элемента не существует
     *
     * @param mixed[] $array
     * @param callable(mixed, string|int): bool $condition
     *
     * @return mixed
     */
    public static function findKey(array $array, callable $condition)
    {
        /** @psalm-suppress MixedAssignment */
        foreach ($array as $key => $value) {
            if ($condition($value, $key) === true) {
                return $key;
            }
        }

        return false;
    }

    /**
     * Возвращает последний ключ элемента, который удовлетворяет условию $condition,
     * возвращает false, если такого элемента не существует
     *
     * @param mixed[] $array
     * @param callable(mixed, string|int): bool $condition
     *
     * @return mixed
     */
    public static function findLastKey(array $array, callable $condition)
    {
        $keys = array_reverse(array_keys($array));
        foreach ($keys as $key) {
            /** @psalm-suppress MixedAssignment */
            $value = $array[$key];
            if ($condition($value, $key) === true) {
                return $key;
            }
        }

        return false;
    }

    /**
     * Возвращает новый массив с переданным ключем и колонкой
     *
     * @param mixed|mixed[][]|mixed[] $array
     * @param array-key $map
     * @param array-key  $column
     *
     * @return mixed[]
     *
     * @psalm-suppress MixedAssignment
     */
    public static function mapAndColumn(array $array, $map, $column = null): array
    {
        $result = [];
        foreach ($array as $value) {
            if (!is_array($value)) {
                continue;
            }
            /**
             * @var array-key $key
             */
            $key = $value[$map] ?? '';
            $set = $value;
            if (!is_null($column)) {
                $set = null;
                if (isset($value[$column])) {
                    $set = $value[$column];
                }
            }
            $result[$key] = $set;
        }

        return $result;
    }
}
