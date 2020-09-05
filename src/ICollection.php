<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\DataType\IArrayObject;

/**
 * Интерфейс коллекции
 */
interface ICollection extends IArrayObject
{
    /**
     * Есть ли элемент с таким ключем
     *
     * @param mixed $key ключ
     */
    public function has($key): bool;

    /**
     * Возвращает элемент по ключу
     *
     * @param mixed $key ключ
     * @param mixed $default значение по умолчанию, возвращается при отсутствии ключа
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Устанавливает значение по ключу
     *
     * @param mixed $key ключ
     * @param mixed $value устанавливаемое значение
     *
     * @return mixed
     */
    public function set($key, $value);

    /**
     * Удаляет элемент по ключу
     *
     * @param mixed $key ключ
     */
    public function delete($key): bool;

    /**
     * Вызывает переданную функцию передавая ключ и значение из коллекции
     *
     * @param callable $callback функция принимающая ключ и значение из коллекции
     *
     * @return static
     */
    public function each(callable $callback);

    /**
     * Вызывает переданную функцию передавая ключ и значение из коллекции и заменяет элемент результатом
     *
     * @param callable $callback функция принимающая ключ и значение из коллекции
     *
     * @return static
     */
    public function map(callable $callback);
}
