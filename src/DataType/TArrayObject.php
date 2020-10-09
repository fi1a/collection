<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

use ArrayIterator;
use Fi1a\Collection\DataType\Exception\OutOfBoundsException;

/**
 * Реализует интерфейсы \ArrayAccess, \Countable
 */
trait TArrayObject
{
    /**
     * @var array
     */
    private $storage = [];

    /**
     * Конструктор.
     *
     * @param mixed[] $data массив, с которым бужет инициализирован объект.
     */
    public function __construct(?array $data = null)
    {
        if (!$data) {
            $data = [];
        }
        $this->exchangeArray($data);
    }

    /**
     * Проверяет наличие.
     *
     * @param mixed $offset ключ.
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->storage);
    }

    /**
     * Возвращает значение.
     *
     * @param mixed $offset ключ.
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->storage[$offset];
    }

    /**
     * Устанавливает значение.
     *
     * @param mixed $offset ключ.
     * @param mixed $value значение.
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->storage[] = $value;

            return;
        }
        $this->storage[$offset] = $value;
    }

    /**
     * Удаляет значение.
     *
     * @param mixed $offset ключ.
     */
    public function offsetUnset($offset)
    {
        unset($this->storage[$offset]);
    }

    /**
     * Заменяет массив.
     *
     * @param mixed[] $input массив со значениями для замены.
     */
    public function exchangeArray(array $input): void
    {
        $this->storage = $input;
    }

    /**
     * Возвращает массив.
     *
     * @return mixed[]
     */
    public function getArrayCopy(): array
    {
        return $this->storage;
    }

    /**
     * Возвращает кол-во элементов в массиве.
     */
    public function count(): int
    {
        return count($this->storage);
    }

    /**
     * Возвращает итератор.
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->storage);
    }

    /**
     * Определяет пустой массив или нет
     */
    public function isEmpty(): bool
    {
        return count($this->storage) === 0;
    }

    /**
     * Возвращает первый элемент массива
     *
     * @return mixed
     */
    public function first()
    {
        if ($this->isEmpty()) {
            throw new OutOfBoundsException('Can\'t determine first item. Array is empty');
        }
        reset($this->storage);

        return current($this->storage);
    }

    /**
     * Возвращает последний элемент
     *
     * @return mixed
     */
    public function last()
    {
        if ($this->isEmpty()) {
            throw new OutOfBoundsException('Can\'t determine last item. Array is empty');
        }
        $value = end($this->storage);
        reset($this->storage);

        return $value;
    }

    /**
     * Очистить массив значений
     */
    public function clear(): IArrayObject
    {
        $this->storage = [];

        return $this;
    }
}
