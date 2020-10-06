<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\DataType\IArrayObject;
use Fi1a\Collection\Exception\ExtractValueException;
use Fi1a\Collection\Exception\InvalidArgumentException;

/**
 * Методы коллекции
 */
trait TCollection
{
    /**
     * Есть ли элемент с таким ключем
     *
     * @param mixed $key ключ
     */
    public function has($key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * Возвращает элемент по ключу
     *
     * @param mixed $key ключ
     * @param mixed $default значение по умолчанию, возвращается при отсутствии ключа
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (!$this->has($key)) {
            return $default;
        }

        return $this[$key];
    }

    /**
     * Устанавливает значение по ключу
     *
     * @param mixed $key ключ
     * @param mixed $value устанавливаемое значение
     *
     * @return static
     */
    public function set($key, $value): ICollection
    {
        $this[$key] = $value;

        return $this;
    }

    /**
     * Удаляет элемент по ключу
     *
     * @param mixed $key ключ
     *
     * @return static
     */
    public function delete($key): ICollection
    {
        if (!$this->has($key)) {
            return $this;
        }
        unset($this[$key]);

        return $this;
    }

    /**
     * Вызывает функцию, передавая ключ и значение из коллекции
     *
     * @param callable $callback функция, принимающая ключ и значение из коллекции
     *
     * @return static
     */
    public function each(callable $callback): ICollection
    {
        foreach ($this as $index => $value) {
            call_user_func($callback, $value, $index);
        }

        return $this;
    }

    /**
     * Вызывает функцию, передавая ключ и значение из коллекции и заменяет элемент результатом
     *
     * @param callable $callback функция, принимающая ключ и значение из коллекции
     *
     * @return static
     */
    public function map(callable $callback): ICollection
    {
        foreach ($this as $index => $value) {
            $this[$index] = call_user_func($callback, $value, $index);
        }

        return $this;
    }

    /**
     * Добавить в коллекцию значение
     *
     * @param mixed $value значение
     *
     * @return static
     */
    public function add($value): ICollection
    {
        $this[] = $value;

        return $this;
    }

    /**
     * Проверяет, присутствует ли в коллекции значение
     *
     * @param mixed $value значение
     * @param bool $strict если true, также проверяет типы значений
     */
    public function contains($value, bool $strict = true): bool
    {
        return in_array($value, $this->getArrayCopy(), $strict);
    }

    /**
     * Возвращает значения переданного ключа, свойства или метода
     *
     * @param string $name ключ, свойство или метод
     *
     * @return mixed[]
     */
    public function column(string $name): array
    {
        $values = [];
        foreach ($this as $value) {
            $values[] = $this->extractValue($value, $name);
        }

        return $values;
    }

    /**
     * Извлекает значение из массива или объекта
     *
     * @param mixed  $object значение
     * @param string $name   название ключа, свойства или метода
     *
     * @return mixed
     *
     * @throws ExtractValueException
     */
    protected function extractValue($object, string $name)
    {
        if (is_array($object) || $object instanceof IArrayObject) {
            return $object[$name];
        }
        if (is_object($object)) {
            if (property_exists($object, $name)) {
                return $object->$name;
            }
            if (method_exists($object, $name)) {
                return $object->{$name}();
            }
        }

        throw new ExtractValueException(sprintf('Name of column "%s" not found', $name));
    }

    /**
     * Сортировка элементов коллекции по значениям переданного ключа, свойства или метода
     *
     * @param string $name ключ, свойство или метод
     * @param string $order направление сортировки
     */
    public function sort(string $name, string $order = self::SORT_ASC): ICollection
    {
        if (!in_array($order, [self::SORT_ASC, self::SORT_DESC], true)) {
            throw new InvalidArgumentException('Invalid order: ' . $order);
        }
        $values = $this->getArrayCopy();
        uasort($values, function ($a, $b) use ($name, $order) {
            $aValue = $this->extractValue($a, $name);
            $bValue = $this->extractValue($b, $name);

            return ($aValue <=> $bValue) * ($order === self::SORT_DESC ? -1 : 1);
        });

        return new static($values);
    }

    /**
     * Возвращает отфильтрованную коллекцию
     */
    public function filter(callable $callback): ICollection
    {
        return new static(array_filter($this->getArrayCopy(), $callback));
    }
}
