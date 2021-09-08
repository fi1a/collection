<?php

declare(strict_types=1);

namespace Fi1a\Collection;

/**
 * Абстрактный класс коллекции экземпляров классов
 */
abstract class AInstanceCollection extends Collection implements IInstanceCollection
{
    /**
     * Конструктор
     *
     * @param mixed[]|null $input массив со значениями
     */
    public function __construct(?array $input = null)
    {
        parent::__construct([]);

        if (!is_array($input)) {
            return;
        }

        /**
         * @var mixed $value
         */
        foreach ($input as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }

    /**
     * @inheritDoc
     */
    public function __call($func, $args)
    {
        $result = [];
        foreach ($this as $item) {
            if (!is_object($item) || !method_exists($item, $func)) {
                $result[] = null;

                continue;
            }
            /**
             * @var mixed[]
             */
            $result[] = call_user_func_array([$item, $func], $args);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($key, $value)
    {
        if (!is_object($value) || !static::isInstance($value)) {
            /**
             * @var mixed
             */
            $value = static::factory($key, $value);
        }

        parent::offsetSet($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function getClone()
    {
        $collection = clone $this;
        $collection->exchangeArray([]);

        /**
         * @var string|int $key
         * @var object $value
         */
        foreach ($this as $key => $value) {
            $collection[$key] = clone $value;
        }

        return $collection;
    }
}
