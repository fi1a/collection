<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

use ArrayIterator;
use Traversable;

use const ReturnTypeWillChange;

/**
 * Реализует интерфейсы \ArrayAccess, \Countable
 */
trait ArrayObjectTrait
{
    /**
     * @var mixed[]|mixed[][]
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
     * @param string|int|null $offset ключ.
     */
    #[ReturnTypeWillChange]
    public function offsetExists($offset): bool
    {
        if (is_null($offset)) {
            $offset = (string) $offset;
        }

        return array_key_exists($offset, $this->storage);
    }

    /**
     * Возвращает значение.
     *
     * @param string|int|null $offset ключ.
     *
     * @return mixed
     */
    #[ReturnTypeWillChange]
    public function &offsetGet($offset)
    {
        $value = null;
        if ($this->offsetExists($offset)) {
            if (is_null($offset)) {
                $offset = (string) $offset;
            }
            /**
             * @var mixed $value
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
    #[ReturnTypeWillChange]
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
     * @param string|int|null $offset ключ.
     */
    #[ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        if (is_null($offset)) {
            $offset = (string) $offset;
        }
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
}
