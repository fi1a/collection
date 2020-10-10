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
     * @return self
     */
    public function set($key, $value)
    {
        $this[$key] = $value;

        return $this;
    }

    /**
     * Удаляет элемент по ключу
     *
     * @param mixed $key ключ
     *
     * @return self
     */
    public function delete($key)
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
     * @return self
     */
    public function each(callable $callback)
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
     * @return self
     */
    public function map(callable $callback)
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
     * @return self
     */
    public function add($value)
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
     *
     * @return static
     */
    public function sort(string $name, string $order = self::SORT_ASC)
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
        $collection = clone $this;
        $collection->exchangeArray($values);

        return $collection;
    }

    /**
     * Возвращает отфильтрованную коллекцию
     *
     * @param callable $callback функция для фильтрации
     *
     * @return static
     */
    public function filter(callable $callback)
    {
        $collection = clone $this;
        $collection->exchangeArray(array_filter($this->getArrayCopy(), $callback));

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
        return $this->filter(function ($item) use ($name, $value) {
            return $value === $this->extractValue($item, $name);
        });
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
        $comparator = function ($a, $b): int {
            if (is_object($a) && is_object($b)) {
                $a = spl_object_id($a);
                $b = spl_object_id($b);
            }

            return $a === $b ? 0 : ($a < $b ? 1 : -1);
        };

        $diff1 = array_udiff($this->getArrayCopy(), $collection->getArrayCopy(), $comparator);
        $diff2 = array_udiff($collection->getArrayCopy(), $this->getArrayCopy(), $comparator);

        return new static(array_merge($diff1, $diff2));
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
        return new static(
            array_uintersect(
                $this->getArrayCopy(),
                $collection->getArrayCopy(),
                function ($a, $b): int {
                    if (is_object($a) && is_object($b)) {
                        $a = spl_object_id($a);
                        $b = spl_object_id($b);
                    }

                    return $a === $b ? 0 : ($a < $b ? 1 : -1);
                }
            )
        );
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
        return new static(array_merge($this->getArrayCopy(), $collection->getArrayCopy()));
    }

    /**
     * Сбросить ключи коллекции
     *
     * @return self
     */
    public function resetKeys()
    {
        $this->exchangeArray(array_values($this->getArrayCopy()));

        return $this;
    }
}
