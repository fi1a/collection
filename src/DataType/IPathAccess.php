<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

/**
 * Интерфейс класса реализующего доступ по пути к значениям
 */
interface IPathAccess extends ArrayObjectInterface
{
    public const PATH_SEPARATOR = ':';

    /**
     * Возвращает значение по пути
     *
     * @param mixed $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Проверяет наличие значения по пути
     *
     * @param mixed $key
     */
    public function has($key): bool;

    /**
     * Установить значение по пути
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function set($key, $value): IPathAccess;

    /**
     * Удалить значение по пути
     *
     * @param mixed $key
     */
    public function delete($key): IPathAccess;

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
