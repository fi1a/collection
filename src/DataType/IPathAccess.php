<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

/**
 * Интерфейс класса реализующего доступ по пути к значениям
 */
interface IPathAccess extends IArrayObject
{
    public const PATH_SEPARATOR = ':';

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
}
