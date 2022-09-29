<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

/**
 * Класс реализует доступ по пути к значениям
 */
class PathAccess extends ArrayObject implements IPathAccess
{
    /**
     * @inheritDoc
     */
    public function get($key, $default = null)
    {
        $paths = $this->getKeys((string) $key);
        $data = $this->getArrayCopy();
        while (count($paths)) {
            $currentKey = array_shift($paths);
            if (
                !(is_array($data) || $data instanceof IArrayObject)
                || (is_array($data) && !array_key_exists($currentKey, $data))
            ) {
                return $default;
            }
            /**
             * @var mixed $data
             */
            $data = $data[$currentKey];
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function has($key): bool
    {
        $paths = $this->getKeys((string) $key);
        $data = $this->getArrayCopy();
        while (count($paths)) {
            $currentKey = array_shift($paths);
            if (!is_array($data) || !array_key_exists($currentKey, $data)) {
                return false;
            }
            /**
             * @var mixed $data
             */
            $data = $data[$currentKey];
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value): IPathAccess
    {
        /**
         * @var mixed[]
         */
        $data = $this->setRecursive(
            $this->getArrayCopy(),
            $this->getKeys((string) $key),
            $value
        );
        $this->exchangeArray($data);

        return $this;
    }

    /**
     * Рекурсивная реализация установления значения.
     *
     * @param mixed[]|IArrayObject $data копия текущего массива.
     * @param string[] $paths стек ключей.
     * @param mixed $value значение.
     *
     * @return mixed[]|IArrayObject
     */
    private function setRecursive($data, array $paths, $value)
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
            /**
             * @psalm-suppress MixedArgument
             */
            $data[$key] = $this->setRecursive($data[$key], $paths, $value);

            return $data;
        }
        /**
         * @var mixed
         */
        $data[$key] = $value;

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function delete($key): IPathAccess
    {
        $this->exchangeArray($this->deleteRecursive($this->getArrayCopy(), $this->getKeys((string) $key)));

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
        if (!array_key_exists($key, $data)) {
            return $data;
        }
        if (count($paths)) {
            /**
             * @psalm-suppress MixedArgument
             */
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
        $current = -1;
        $index = 0;
        $paths = [];
        do {
            $current++;
            $symbol = mb_substr($path, $current, 1);
            $prevSymbol = mb_substr($path, $current - 1, 1);

            if ($symbol === (string) static::PATH_SEPARATOR && $prevSymbol !== '\\') {
                $index++;

                continue;
            }
            if (!isset($paths[$index])) {
                $paths[$index] = '';
            }
            if ($symbol === (string) static::PATH_SEPARATOR && $prevSymbol === '\\') {
                $paths[$index] = mb_substr($paths[$index], 0, -1);
            }
            /**
             * @psalm-suppress PossiblyUndefinedArrayOffset
             */
            $paths[$index] .= $symbol;
        } while ($current < mb_strlen($path));

        return $paths;
    }

    /**
     * @inheritDoc
     */
    public function getBool(string $path, ?bool $default = null): ?bool
    {
        /**
         * @var mixed
         */
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
        /**
         * @var mixed
         */
        $value = $this->get($path, $default);
        if (is_null($value) || $value === '') {
            return null;
        }

        return (int) $value;
    }
}
