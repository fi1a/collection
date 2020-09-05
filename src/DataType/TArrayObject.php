<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

use ArrayIterator;

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
     * @param mixed[] $data массив с которым бужет инициализирован объект.
     */
    public function __construct(array $data = [])
    {
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
    public function offsetSet($offset, $value): void
    {
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
     * Клонирование.
     *
     * @return static возвращает новый экземпляр класса со значениями текущего.
     */
    public function getClone()
    {
        return new static($this->getArrayCopy());
    }
}
