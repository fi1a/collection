<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

use ArrayIterator;
use Fi1a\Collection\DataType\Exception\OutOfBoundsException;
use Traversable;

/**
 * Реализует интерфейсы \ArrayAccess, \Countable
 */
trait TArrayObject
{
    /**
     * @var array
     */
    protected $storage = [];

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
     * @param string|int $offset ключ.
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->storage);
    }

    /**
     * Возвращает значение.
     *
     * @param string|int $offset ключ.
     *
     * @return mixed
     */
    public function &offsetGet($offset)
    {
        $value = null;
        if ($this->offsetExists($offset)) {
            /**
             * @var mixed
             */
            $value = &$this->storage[$offset];
        }

        return $value;
    }

    /**
     * Устанавливает значение.
     *
     * @param string|int|null $key ключ.
     * @param mixed $value значение.
     */
    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->storage[] = $value;

            return;
        }
        $this->storage[$key] = $value;
    }

    /**
     * Удаляет значение.
     *
     * @param string|int $offset ключ.
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
    public function getIterator(): Traversable
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
     * Возвращает первый элемент
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
        /**
         * @var mixed
         */
        $value = end($this->storage);
        reset($this->storage);

        return $value;
    }

    /**
     * Очистить массив значений
     *
     * @return static
     */
    public function clear()
    {
        $this->storage = [];

        return $this;
    }
}
