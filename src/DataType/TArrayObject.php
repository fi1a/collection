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
     * @param string|int|null $offset ключ.
     */
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

    /**
     * Проверяет, присутствует ли в массиве указанное значение
     *
     * @param mixed $value
     */
    public function hasValue($value): bool
    {
        return in_array($value, $this->storage, true);
    }

    /**
     * Возвращает ключи массива
     *
     * @return mixed[]
     */
    public function keys(): array
    {
        return array_keys($this->storage);
    }

    /**
     * Есть ли элемент с таким ключем
     *
     * @param string|int|null $key ключ
     */
    public function has($key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * Возвращает элемент по ключу
     *
     * @param string|int|null $key ключ
     * @param mixed $default значение по умолчанию, возвращается при отсутствии ключа
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (!$this->has($key)) {
            return $default;
        }
        if (is_null($key)) {
            $key = (string) $key;
        }

        return $this[$key];
    }

    /**
     * Устанавливает значение по ключу
     *
     * @param string|int|null $key ключ
     * @param mixed $value устанавливаемое значение
     *
     * @return static
     */
    public function set($key, $value)
    {
        if (is_null($key)) {
            $key = (string) $key;
        }
        $this->storage[$key] = $value;

        return $this;
    }

    /**
     * Удаляет элемент по ключу, возвращает удаленное значение
     *
     * @param string|int|null $key ключ
     *
     * @return mixed
     */
    public function delete($key)
    {
        /**
         * @var mixed $prev
         */
        $prev = $this->get($key);
        if ($this->has($key)) {
            if (is_null($key)) {
                $key = (string) $key;
            }
            unset($this[$key]);
        }

        return $prev;
    }

    /**
     * Удаляет элемент по ключу, если значение равно переданному. Если элемент удален, возвращает true.
     *
     * @param string|int|null $key ключ
     * @param mixed $value
     */
    public function deleteIf($key, $value): bool
    {
        if ($this->get($key) === $value) {
            $this->delete($key);

            return true;
        }

        return false;
    }

    /**
     * Устанавливает значение по ключу, если значение уже есть в массиве, возвращает его
     *
     * @param string|int|null $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function put($key, $value)
    {
        /**
         * @var mixed $prev
         */
        $prev = $this->get($key);
        $this->set($key, $value);

        return $prev;
    }

    /**
     * Устанавливает значение по ключу, если его нет. Возвращает предыдущее значение
     *
     * @param string|int|null $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function putIfAbsent($key, $value)
    {
        /**
         * @var mixed $prev
         */
        $prev = $this->get($key);
        if (is_null($prev)) {
            $this->set($key, $value);
        }

        return $prev;
    }

    /**
     * Заменяет значение элемента по ключу, только если есть значение. Возвращает предыдущее значение
     *
     * @param string|int|null $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function replace($key, $value)
    {
        /**
         * @var mixed $prev
         */
        $prev = $this->get($key);
        if ($this->has($key)) {
            $this->set($key, $value);
        }

        return $prev;
    }

    /**
     * Заменяет значение элемента по ключу, только если текущее значение равно $oldValue.
     * Если элемент заменен, возвращает true.
     *
     * @param string|int|null $key
     * @param mixed $oldValue
     * @param mixed $newValue
     */
    public function replaceIf($key, $oldValue, $newValue): bool
    {
        if ($this->get($key) === $oldValue) {
            $this->set($key, $newValue);

            return true;
        }

        return false;
    }
}
