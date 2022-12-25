<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\DataType\MapArrayObject;

use const ReturnTypeWillChange;

/**
 * Коллекция
 */
class Collection extends MapArrayObject implements CollectionInterface
{
    use TypedValueTrait;

    /**
     * @inheritDoc
     */
    public function __construct(string $type = 'mixed', ?array $data = null)
    {
        $this->type = $type;
        parent::__construct($data);
    }

    /**
     * @inheritDoc
     */
    #[ReturnTypeWillChange]
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

    /**
     * @inheritDoc
     */
    public function set($key, $value)
    {
        $this->validateType($value);

        return parent::set($key, $value);
    }
}
