<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

use ArrayAccess;
use Countable;
use IteratorAggregate;

/**
 * Интерфейс IArrayObject
 */
interface IArrayObject extends IteratorAggregate, ArrayAccess, Countable
{
    /**
     * Creates a copy of the ArrayObject.
     *
     * @return mixed[]
     */
    public function getArrayCopy();

    /**
     * Exchange the array for another one.
     *
     * @param mixed[] $input массив со значениями для замены.
     *
     * @return void
     */
    public function exchangeArray(array $input);

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
     * @param string|int $key ключ
     */
    public function has($key): bool;

    /**
     * Возвращает элемент по ключу
     *
     * @param string|int $key ключ
     * @param mixed $default значение по умолчанию, возвращается при отсутствии ключа
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Устанавливает значение по ключу
     *
     * @param string|int $key ключ
     * @param mixed $value устанавливаемое значение
     *
     * @return self
     */
    public function set($key, $value);

    /**
     * Удаляет элемент по ключу, возвращает удаленное значение
     *
     * @param string|int $key ключ
     *
     * @return mixed
     */
    public function delete($key);

    /**
     * Удаляет элемент по ключу, если значение равно переданному. Если элемент удален, возвращает true.
     *
     * @param string|int $key ключ
     * @param mixed $value
     */
    public function deleteIf($key, $value): bool;

    /**
     * Устанавливает значение по ключу, если значение уже есть в массиве, возвращает его
     *
     * @param string|int $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function put($key, $value);

    /**
     * Устанавливает значение по ключу, если его нет. Возвращает предыдущее значение
     *
     * @param string|int $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function putIfAbsent($key, $value);

    /**
     * Заменяет значение элемента по ключу, только если есть значение. Возвращает предыдущее значение
     *
     * @param string|int $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function replace($key, $value);

    /**
     * Заменяет значение элемента по ключу, только если текущее значение равно $oldValue.
     * Если элемент заменен, возвращает true.
     *
     * @param string|int $key
     * @param mixed $oldValue
     * @param mixed $newValue
     */
    public function replaceIf($key, $oldValue, $newValue): bool;
}
