<?php

declare(strict_types=1);

namespace Fi1a\Collection;

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
}
