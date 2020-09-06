<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

use const FILTER_DEFAULT;

/**
 * Интерфейс класса реализующего доступ по пути к значениям
 */
interface IPathAccess extends IArrayObject
{
    public const PATH_SEPARATOR = ':';

    /**
     * Возвращает значение, используя путь.
     *
     * @param string     $path    путь до значения.
     * @param mixed|null $default если значения нет, будет возвращено это значение.
     *
     * @return mixed
     */
    public function get(string $path, $default = null);

    /**
     * Определяет есть ли значение по указанному пути.
     *
     * @param string $path путь до значения.
     */
    public function has(string $path): bool;

    /**
     * Устанавливает значение, используя путь.
     *
     * @param string $path  путь до значения.
     * @param mixed  $value значение, которое будет установлено.
     */
    public function set(string $path, $value): IPathAccess;

    /**
     * Удаляет значение, используя путь.
     *
     * @param string $path путь до значения.
     */
    public function delete(string $path): IPathAccess;

    /**
     * Возвращает значение с преобразованием к boolean.
     *
     * @param string    $path путь до значения.
     * @param bool|null $default значение по умолчанию.
     */
    public function getBool(string $path, ?bool $default = null): ?bool;

    /**
     * Возвращает значение с преобразованием к integer.
     *
     * @param string   $path путь до значения.
     * @param int|null $default значение по умолчанию.
     */
    public function getInt(string $path, ?int $default = null): ?int;

    /**
     * Фильтрует значение.
     *
     * @see http://php.net/manual/ru/function.filter-var.php
     *
     * @param string $path путь до значения.
     * @param mixed   $default значение по умолчанию.
     * @param int    $filter идентификатор (ID) применяемого фильтра.
     * @param mixed|mixed[]  $options ассоциативный массив параметров либо логическая дизъюнкция (операция ИЛИ) флагов.
     *
     * @return mixed
     */
    public function filter(string $path, $default = null, int $filter = FILTER_DEFAULT, $options = []);
}
