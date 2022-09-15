<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

use Fi1a\Collection\TTypedValue;

/**
 * Объект-массив с типизацией значений
 */
class TypedValueArray extends ArrayObject
{
    use TTypedValue;

    /**
     * @inheritDoc
     */
    public function __construct(string $type, ?array $data = null)
    {
        $this->type = $type;
        parent::__construct($data);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($key, $value)
    {
        $this->validateType($value);
        parent::offsetSet($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function exchangeArray(array $input): void
    {
        /**
         * @var mixed $value
         */
        foreach ($input as $value) {
            $this->validateType($value);
        }
        parent::exchangeArray($input);
    }
}
