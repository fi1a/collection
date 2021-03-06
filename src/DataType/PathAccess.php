<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

use const FILTER_DEFAULT;

/**
 * Класс реализует доступ по пути к значениям
 */
class PathAccess extends ArrayObject implements IPathAccess
{
    /**
     * @inheritDoc
     */
    public function get(string $path, $default = null)
    {
        $paths = $this->getKeys((string) $path);
        $data = $this->getArrayCopy();
        while (count($paths)) {
            $key = array_shift($paths);
            if (!(is_array($data) || $data instanceof IArrayObject) || !array_key_exists($key, $data)) {
                return $default;
            }
            $data = $data[$key];
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function has(string $path): bool
    {
        $paths = $this->getKeys((string) $path);
        $data = $this->getArrayCopy();
        while (count($paths)) {
            $key = array_shift($paths);
            if (!is_array($data) || !array_key_exists($key, $data)) {
                return false;
            }
            $data = $data[$key];
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function set(string $path, $value): IPathAccess
    {
        $this->exchangeArray(
            $this->setRecursive(
                $this->getArrayCopy(),
                $this->getKeys((string) $path),
                $value
            )
        );

        return $this;
    }

    /**
     * Рекурсивная реализация установления значения.
     *
     * @param mixed[] $data копия текущего массива.
     * @param string[] $paths стек ключей.
     * @param mixed $value значение.
     *
     * @return mixed[]
     */
    private function setRecursive(array $data, array $paths, $value): array
    {
        $key = array_shift($paths);
        $count = count($paths);
        if (!isset($data[$key])) {
            $data[$key] = $count ? [] : null;
        }
        if ($count) {
            if (!(is_array($data[$key]) || $data[$key] instanceof IArrayObject)) {
                $data[$key] = [];
            }
            $data[$key] = $this->setRecursive($data[$key], $paths, $value);

            return $data;
        }
        $data[$key] = $value;

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $path): IPathAccess
    {
        $this->exchangeArray($this->deleteRecursive($this->getArrayCopy(), $this->getKeys((string) $path)));

        return $this;
    }

    /**
     * Рекурсивная реализация удаления значения массива
     *
     * @param mixed[] $data копия текущего массива.
     * @param string[] $paths стек ключей.
     *
     * @return mixed[]
     */
    private function deleteRecursive(array $data, array $paths): array
    {
        $key = array_shift($paths);
        if (!is_array($data) || !array_key_exists($key, $data)) {
            return $data;
        }
        if (count($paths)) {
            $data[$key] = $this->deleteRecursive($data[$key], $paths);

            return $data;
        }
        unset($data[$key]);

        return $data;
    }

    /**
     * Возвращает массив ключей
     *
     * @param string $path путь
     *
     * @return string[]
     */
    private function getKeys(string $path): array
    {
        return explode(static::PATH_SEPARATOR, $path);
    }

    /**
     * @inheritDoc
     */
    public function getBool(string $path, ?bool $default = null): ?bool
    {
        $value = $this->get($path, $default);
        if (is_null($value) || $value === '') {
            return null;
        }
        if ($value === 'false') {
            return false;
        }

        return (bool) $value;
    }

    /**
     * @inheritDoc
     */
    public function getInt(string $path, ?int $default = null): ?int
    {
        $value = $this->get($path, $default);
        if (is_null($value) || $value === '') {
            return null;
        }

        return (int) $value;
    }

    /**
     * @inheritDoc
     */
    public function filter(string $path, $default = null, int $filter = FILTER_DEFAULT, $options = [])
    {
        return filter_var($this->get($path, $default), $filter, $options);
    }
}
