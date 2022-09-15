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
     * Вызывает функцию, передавая ключ и значение из коллекции
     *
     * @param callable(mixed, mixed):void $callback функция, принимающая ключ и значение из коллекции
     *
     * @return static
     */
    public function each(callable $callback)
    {
        /**
         * @var string|int $index
         * @var mixed $value
         */
        foreach ($this as $index => $value) {
            call_user_func($callback, $value, $index);
        }

        return $this;
    }

    /**
     * Вызывает функцию, передавая ключ и значение из коллекции и заменяет элемент результатом
     *
     * @param callable(mixed, mixed):mixed $callback функция, принимающая ключ и значение из коллекции
     *
     * @return static
     */
    public function map(callable $callback)
    {
        /**
         * @var string|int $index
         * @var mixed $value
         */
        foreach ($this as $index => $value) {
            /**
             * @var mixed
             */
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
    public function add($value)
    {
        /**
         * @var mixed
         */
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
        return in_array($value, $this->storage, $strict);
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
        /**
         * @var mixed $value
         */
        foreach ($this as $value) {
            /**
             * @var mixed
             */
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
     *
     * @return static
     */
    public function sort(string $name, string $order = self::SORT_ASC)
    {
        if (!in_array($order, [self::SORT_ASC, self::SORT_DESC], true)) {
            throw new InvalidArgumentException('Invalid order: ' . $order);
        }
        $values = $this->getArrayCopy();
        $sort = /**
         * @param mixed $a
         * @param mixed $b
         *
         * @return int
         */function ($a, $b) use ($name, $order): int {
            /**
             * @var mixed
             */
            $aValue = $this->extractValue($a, $name);
            /**
             * @var mixed
             */
            $bValue = $this->extractValue($b, $name);

            return ($aValue <=> $bValue) * ($order === self::SORT_DESC ? -1 : 1);
};
        uasort($values, $sort);
        $collection = clone $this;
        $collection->exchangeArray($values);

        return $collection;
    }

    /**
     * Возвращает отфильтрованную коллекцию
     *
     * @param callable(mixed, mixed=):scalar $callback функция для фильтрации
     *
     * @return static
     */
    public function filter(callable $callback)
    {
        $collection = clone $this;
        $collection->exchangeArray(array_filter($this->storage, $callback));

        return $collection;
    }

    /**
     * Возвразает коллекцию с элементами у которых значение ключа, свойства или метода равно переданному значению
     *
     * @param string $name ключ, свойство или метод
     * @param mixed $value значение для сравнения
     *
     * @return static
     */
    public function where(string $name, $value)
    {
        $filter = /**
     * @param mixed $item
     */function ($item) use ($name, $value): bool {
            return $value === $this->extractValue($item, $name);
};

        return $this->filter($filter);
    }

    /**
     * Возвращает новую коллекцию с расходящимися элементами текущей коллекции с переданной
     *
     * @param ICollection $collection коллекция для вычисления расхождения
     *
     * @return static
     */
    public function diff(ICollection $collection)
    {
        $comparator = /**
        * @param mixed $a
         * @param mixed $b
         */function ($a, $b): int {
    if (is_object($a) && is_object($b)) {
        $a = spl_object_id($a);
        $b = spl_object_id($b);
    }

            return $a === $b ? 0 : ($a < $b ? 1 : -1);
};

        $diff1 = array_udiff($this->getArrayCopy(), $collection->getArrayCopy(), $comparator);
        $diff2 = array_udiff($collection->getArrayCopy(), $this->getArrayCopy(), $comparator);
        $cloneCollection = clone $this;
        $cloneCollection->exchangeArray(array_merge($diff1, $diff2));

        return $cloneCollection;
    }

    /**
     * Возвращает новую коллекцию с пересечением элементов текущей коллекции с переданной
     *
     * @param ICollection $collection коллекция для вычисления пересечения
     *
     * @return static
     */
    public function intersect(ICollection $collection)
    {
        $function = /**
         * @param mixed $a
         * @param mixed $b
         */function ($a, $b): int {
    if (is_object($a) && is_object($b)) {
        $a = spl_object_id($a);
        $b = spl_object_id($b);
    }

            return $a === $b ? 0 : ($a < $b ? 1 : -1);
};
        $cloneCollection = clone $this;
        $cloneCollection->exchangeArray(array_uintersect(
            $this->getArrayCopy(),
            $collection->getArrayCopy(),
            $function
        ));

        return $cloneCollection;
    }

    /**
     * Объединяет элементы текущей коллекции с элементами переданной и возвращает новую коллекцию
     *
     * @param ICollection $collection коллекция для объединения
     *
     * @return static
     */
    public function merge(ICollection $collection)
    {
        $cloneCollection = clone $this;
        $cloneCollection->exchangeArray(array_merge($this->getArrayCopy(), $collection->getArrayCopy()));

        return $cloneCollection;
    }

    /**
     * Сбросить ключи коллекции
     *
     * @return static
     */
    public function resetKeys()
    {
        $this->storage = array_values($this->storage);

        return $this;
    }

    /**
     * Итеративно уменьшает коллекцию к единственному значению, используя callback-функцию
     *
     * @param callable(mixed, mixed):mixed $callback
     * @param mixed    $initial
     *
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        return array_reduce($this->storage, $callback, $initial);
    }
}
