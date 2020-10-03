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
        foreach ($this as $ind => $value) {
            call_user_func($callback, $ind, $value);
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
        foreach ($this as $ind => $value) {
            $this[$ind] = call_user_func($callback, $ind, $value);
        }

        return $this;
    }
}
