<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

/**
 * Интерфейс IMapArrayObject
 */
interface IMapArrayObject extends IArrayObject
{
    public const SORT_ASC = 'asc';

    public const SORT_DESC = 'desc';

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
     * @return self
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
     * @return self
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
     * @return self
     */
    public function add($value);

    /**
     * Вызывает функцию, передавая ключ и значение из коллекции
     *
     * @param callable(mixed, mixed):void $callback функция, принимающая ключ и значение из коллекции
     *
     * @return self
     */
    public function each(callable $callback);

    /**
     * Вызывает функцию, передавая ключ и значение из коллекции и заменяет элемент результатом
     *
     * @param callable(mixed, mixed):mixed $callback функция, принимающая ключ и значение из коллекции
     *
     * @return self
     */
    public function map(callable $callback);

    /**
     * Проверяет, присутствует ли в коллекции значение
     *
     * @param mixed $value значение
     * @param bool $strict если true, также проверяет типы значений
     */
    public function contains($value, bool $strict = true): bool;

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
     * @param IArrayObject $collection коллекция для вычисления расхождения
     *
     * @return static
     */
    public function diff(IArrayObject $collection);

    /**
     * Возвращает новую коллекцию с пересечением элементов текущей коллекции с переданной
     *
     * @param IArrayObject $collection коллекция для вычисления пересечения
     *
     * @return static
     */
    public function intersect(IArrayObject $collection);

    /**
     * Объединяет элементы текущей коллекции с элементами переданной и возвращает новую коллекцию
     *
     * @param IArrayObject $collection коллекция для объединения
     *
     * @return static
     */
    public function merge(IArrayObject $collection);

    /**
     * Сбросить ключи коллекции
     *
     * @return self
     */
    public function resetKeys();

    /**
     * Итеративно уменьшает коллекцию к единственному значению, используя callback-функцию
     *
     * @param callable(mixed, mixed):mixed $callback
     * @param mixed    $initial
     *
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null);
}
