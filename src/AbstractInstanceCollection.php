<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\DataType\MapArrayObject;

use const ReturnTypeWillChange;

/**
 * Абстрактный класс коллекции экземпляров классов
 */
abstract class AbstractInstanceCollection extends MapArrayObject implements InstanceCollectionInterface
{
    /**
     * Возвращает экземпляр класса элемента коллекции
     *
     * @param string|int|null $key ключ
     * @param mixed $value значение
     *
     * @return mixed
     */
    abstract protected function factory($key, $value);

    /**
     * Определяет является ли значение экземпляром класса элемента коллекции
     *
     * @param mixed $value значение
     */
    abstract protected function isInstance($value): bool;

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
    #[ReturnTypeWillChange]
    public function offsetSet($key, $value)
    {
        if (!is_object($value) || !$this->isInstance($value)) {
            /**
             * @var mixed $value
             * @psalm-suppress PossiblyNullArgument
             */
            $value = $this->factory($key, $value);
        }

        parent::offsetSet($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value)
    {
        if (!is_object($value) || !$this->isInstance($value)) {
            /**
             * @var mixed $value
             * @psalm-suppress PossiblyNullArgument
             */
            $value = $this->factory($key, $value);
        }

        return parent::set($key, $value);
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

    /**
     * @inheritDoc
     */
    public function exchangeArray(array $input): void
    {
        /**
         * @var mixed $value
         */
        foreach ($input as $key => $value) {
            if (!is_object($value) || !$this->isInstance($value)) {
                /**
                 * @psalm-suppress MixedAssignment
                 */
                $input[$key] = $this->factory($key, $value);
            }
        }
        parent::exchangeArray($input);
    }
}
