<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

use Fi1a\Collection\Helpers\ArrayHelper;

/**
 * Интерфейс IMapArrayObject
 */
interface IMapArrayObject extends IArrayObject
{
    public const SORT_ASC = ArrayHelper::SORT_ASC;

    public const SORT_DESC = ArrayHelper::SORT_DESC;

    /**
     * Определяет пустой массив или нет
     */
    public function isEmpty(): bool;

    /**
     * Возвращает первый элемент
     *
     * @return mixed
     */
    public function first();

    /**
     * Возвращает последний элемент
     *
     * @return mixed
     */
    public function last();

    /**
     * Очистить массив значений
     *
     * @return $this
     */
    public function clear();

    /**
     * Проверяет, присутствует ли в массиве указанное значение
     *
     * @param mixed $value
     */
    public function hasValue($value): bool;

    /**
     * Возвращает ключи массива
     *
     * @return mixed[]
     */
    public function keys(): array;

    /**
     * Есть ли элемент с таким ключем
     *
     * @param string|int|null $key ключ
     */
    public function has($key): bool;

    /**
     * Возвращает элемент по ключу
     *
     * @param string|int|null $key ключ
     * @param mixed $default значение по умолчанию, возвращается при отсутствии ключа
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Устанавливает значение по ключу
     *
     * @param string|int|null $key ключ
     * @param mixed $value устанавливаемое значение
     *
     * @return $this
     */
    public function set($key, $value);

    /**
     * Удаляет элемент по ключу, возвращает удаленное значение
     *
     * @param string|int|null $key ключ
     *
     * @return mixed
     */
    public function delete($key);

    /**
     * Удаляет элемент по ключу, если значение равно переданному. Если элемент удален, возвращает true.
     *
     * @param string|int|null $key ключ
     * @param mixed $value
     */
    public function deleteIf($key, $value): bool;

    /**
     * Устанавливает значение по ключу, если значение уже есть в массиве, возвращает его
     *
     * @param string|int|null $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function put($key, $value);

    /**
     * Устанавливает значение по ключу, если его нет. Возвращает предыдущее значение
     *
     * @param string|int|null $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function putIfAbsent($key, $value);

    /**
     * Заменяет значение элемента по ключу, только если есть значение. Возвращает предыдущее значение
     *
     * @param string|int|null $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function replace($key, $value);

    /**
     * Заменяет значение элемента по ключу, только если текущее значение равно $oldValue.
     * Если элемент заменен, возвращает true.
     *
     * @param string|int|null $key
     * @param mixed $oldValue
     * @param mixed $newValue
     */
    public function replaceIf($key, $oldValue, $newValue): bool;

    /**
     * Добавить в коллекцию значение
     *
     * @param mixed $value значение
     *
     * @return $this
     */
    public function add($value);

    /**
     * Вызывает функцию, передавая ключ и значение из коллекции
     *
     * @param callable(mixed, mixed):void $callback функция, принимающая ключ и значение из коллекции
     *
     * @return $this
     */
    public function each(callable $callback);

    /**
     * Вызывает функцию, передавая ключ и значение из коллекции и заменяет элемент результатом
     *
     * @param callable(mixed, mixed):mixed $callback функция, принимающая ключ и значение из коллекции
     *
     * @return $this
     */
    public function map(callable $callback);

    /**
     * Возвращает значения переданного ключа, свойства или метода
     *
     * @param string $name ключ, свойство или метод
     *
     * @return mixed[]
     */
    public function column(string $name): array;

    /**
     * Сортировка элементов коллекции по значениям переданного ключа, свойства или метода
     *
     * @param string $name ключ, свойство или метод
     * @param string $order направление сортировки
     *
     * @return static
     */
    public function sort(string $name, string $order = self::SORT_ASC);

    /**
     * Возвращает отфильтрованную коллекцию
     *
     * @param callable(mixed, mixed=):scalar $callback функция для фильтрации
     *
     * @return static
     */
    public function filter(callable $callback);

    /**
     * Возвразает коллекцию с элементами у которых значение ключа, свойства или метода равно переданному значению
     *
     * @param string $name ключ, свойство или метод
     * @param mixed $value значение для сравнения
     *
     * @return static
     */
    public function where(string $name, $value);

    /**
     * Возвращает новую коллекцию с расходящимися элементами текущей коллекции с переданной
     *
     * @param IArrayObject|mixed[] $collection коллекция для вычисления расхождения
     *
     * @return static
     */
    public function diff($collection);

    /**
     * Возвращает новую коллекцию с пересечением элементов текущей коллекции с переданной
     *
     * @param IArrayObject|mixed[] $collection коллекция для вычисления пересечения
     *
     * @return static
     */
    public function intersect($collection);

    /**
     * Объединяет элементы текущей коллекции с элементами переданной и возвращает новую коллекцию
     *
     * @param IArrayObject|mixed[] $collection коллекция для объединения
     *
     * @return static
     */
    public function merge($collection);

    /**
     * Сбросить ключи коллекции
     *
     * @return $this
     */
    public function resetKeys();

    /**
     * Итеративно уменьшает коллекцию к единственному значению, используя callback-функцию
     *
     * @param callable(mixed, mixed): mixed $callback
     * @param mixed    $initial
     *
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null);

    /**
     * Итеративно уменьшает коллекцию к единственному значению  в обратном порядке, используя callback-функцию
     *
     * @param callable(mixed, mixed): mixed $callback
     * @param mixed $initial
     *
     * @return mixed
     */
    public function reduceRight(callable $callback, $initial = null);

    /**
     * Оборачивает значения и возвращает новую коллекцию
     *
     * @return static
     */
    public function wraps(string $prefix, ?string $suffix = null);

    /**
     * Объединяет элементы в строку
     */
    public function join(string $separator): string;

    /**
     * Вставить значения
     *
     * @param mixed[] $values
     *
     * @return $this
     */
    public function insert(int $index, array $values);

    /**
     * Возвращает ключ первого элемента
     *
     * @return string|int|false
     */
    public function firstKey();

    /**
     * Возвращает ключ последнего элемента
     *
     * @return string|int|false
     */
    public function lastKey();

    /**
     * Переключает значения
     *
     * @param string|int|null $key
     * @param mixed           $firstValue
     * @param mixed           $secondValue
     *
     * @return $this
     */
    public function toggleValue($key, $firstValue, $secondValue);

    /**
     * Возвращает true, если все элементы удовлетворяют условию
     *
     * @param callable(mixed, string|int): bool $condition
     */
    public function every(callable $condition): bool;

    /**
     * Возвращает коллекцию без элементов удовлетворяющих условию
     *
     * @param callable(mixed, string|int): bool $condition
     *
     * @return static
     */
    public function without(callable $condition);

    /**
     * Возвращает коллекцию с элементами удовлетворяющими условию
     *
     * @param callable(mixed, string|int): bool $condition
     *
     * @return static
     */
    public function with(callable $condition);

    /**
     * Возвращает коллекцию, опуская заданное количество элементов с начала
     *
     * @return static
     */
    public function drop(int $count);

    /**
     * Возвращает коллекцию, опуская заданное количество элементов с конца
     *
     * @return static
     */
    public function dropRight(int $count);

    /**
     * Возвращает первый элемент, который удовлетворяет условию $condition,
     * возвращает false, если такого элемента не существует
     *
     * @param callable(mixed, string|int): bool $condition
     *
     * @return mixed
     */
    public function findValue(callable $condition);

    /**
     * Возвращает последний элемент, который удовлетворяет условию $condition,
     * возвращает false, если такого элемента не существует
     *
     * @param callable(mixed, string|int): bool $condition
     *
     * @return mixed
     */
    public function findLastValue(callable $condition);

    /**
     * Возвращает первый ключ элемента, который удовлетворяет условию $condition,
     * возвращает false, если такого элемента не существует
     *
     * @param callable(mixed, string|int): bool $condition
     *
     * @return mixed
     */
    public function findKey(callable $condition);

    /**
     * Возвращает последний ключ элемента, который удовлетворяет условию $condition,
     * возвращает false, если такого элемента не существует
     *
     * @param callable(mixed, string|int): bool $condition
     *
     * @return mixed
     */
    public function findLastKey(callable $condition);

    /**
     * Возвращает новый массив с переданным ключем и колонкой
     *
     * @param string|int     $map
     * @param string|int|null $column
     *
     * @return static
     */
    public function mapAndColumn($map, $column = null);
}
