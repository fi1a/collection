<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\Exception\InvalidArgumentException;
use Fi1a\Format\Formatter;

/**
 * Очередь с типизацией значений
 */
class TypedValueQueue extends Queue implements ITypedValueQueue
{
    use TTypedValue;

    /**
     * @var string
     */
    protected $type;

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
    public function getType(): string
    {
        return $this->type;
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
    public function offsetSet($key, $value)
    {
        $this->validateType($value);
        parent::offsetSet($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function addBegin($value): bool
    {
        $this->validateType($value);

        return parent::addBegin($value);
    }

    /**
     * @inheritDoc
     */
    public function addEnd($value): bool
    {
        $this->validateType($value);

        return parent::addEnd($value);
    }

    /**
     * Валидация типа значения. Если тип не совпадает с объявленным, выбрасывает исключение.
     *
     * @param mixed $value
     */
    private function validateType($value): void
    {
        if (!$this->checkValueType($this->getType(), $value)) {
            throw new InvalidArgumentException(
                Formatter::format('Value {{0}} not is type {{1}}', [$value, $this->getType()])
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function offerBegin($value): bool
    {
        try {
            return $this->addBegin($value);
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function offerEnd($value): bool
    {
        try {
            return $this->addEnd($value);
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }
}
