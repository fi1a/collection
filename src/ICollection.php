<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\DataType\IArrayObject;

/**
 * Интерфейс коллекции
 */
interface ICollection extends IArrayObject
{
    public const SORT_ASC = 'asc';

    public const SORT_DESC = 'desc';

    /**
     * Вызывает функцию, передавая ключ и значение из коллекции
     *
     * @param callable $callback функция, принимающая ключ и значение из коллекции
     *
     * @return self
     */
    public function each(callable $callback);

    /**
     * Вызывает функцию, передавая ключ и значение из коллекции и заменяет элемент результатом
     *
     * @param callable $callback функция, принимающая ключ и значение из коллекции
     *
     * @return self
     */
    public function map(callable $callback);

    /**
     * Добавить в коллекцию значение
     *
     * @param mixed $value значение
     *
     * @return self
     */
    public function add($value);

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
     * @param callable $callback функция для фильтрации
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
     * @param ICollection $collection коллекция для вычисления расхождения
     *
     * @return static
     */
    public function diff(ICollection $collection);

    /**
     * Возвращает новую коллекцию с пересечением элементов текущей коллекции с переданной
     *
     * @param ICollection $collection коллекция для вычисления пересечения
     *
     * @return static
     */
    public function intersect(ICollection $collection);

    /**
     * Объединяет элементы текущей коллекции с элементами переданной и возвращает новую коллекцию
     *
     * @param ICollection $collection коллекция для объединения
     *
     * @return static
     */
    public function merge(ICollection $collection);

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
