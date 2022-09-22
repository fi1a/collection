<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\DataType\ArrayObject;
use Fi1a\Collection\Exception\EmptyQueueException;

/**
 * Очередь
 */
class Queue extends ArrayObject implements IQueue
{
    /**
     * @var int
     */
    protected $beginIndex = 0;

    /**
     * @var int
     */
    protected $endIndex = 0;

    /**
     * @inheritDoc
     */
    public function exchangeArray(array $input): void
    {
        parent::exchangeArray(array_values($input));
        $this->endIndex = $this->count();
        $this->beginIndex = 0;
    }

    /**
     * @inheritDoc
     */
    public function addBegin($value): bool
    {
        array_unshift($this->storage, $value);
        $this->endIndex++;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function addEnd($value): bool
    {
        $this->storage[] = $value;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function removeBegin()
    {
        if ($this->isEmpty()) {
            throw new EmptyQueueException('Queue is empty');
        }

        return $this->pollBegin();
    }

    /**
     * @inheritDoc
     */
    public function removeEnd()
    {
        if ($this->isEmpty()) {
            throw new EmptyQueueException('Queue is empty');
        }

        return $this->pollEnd();
    }

    /**
     * @inheritDoc
     */
    public function pollBegin()
    {
        if ($this->isEmpty()) {
            return null;
        }

        /**
         * @var mixed $value
         */
        $value = $this->offsetGet($this->beginIndex);
        $this->offsetUnset($this->beginIndex);
        $this->beginIndex++;

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function pollEnd()
    {
        if ($this->isEmpty()) {
            return null;
        }

        /**
         * @var mixed $value
         */
        $value = $this->offsetGet($this->endIndex - 1);
        $this->offsetUnset($this->endIndex - 1);
        $this->endIndex--;

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function beginElement()
    {
        if ($this->isEmpty()) {
            throw new EmptyQueueException('Queue is empty');
        }

        return $this->peekBegin();
    }

    /**
     * @inheritDoc
     */
    public function endElement()
    {
        if ($this->isEmpty()) {
            throw new EmptyQueueException('Queue is empty');
        }

        return $this->peekEnd();
    }

    /**
     * @inheritDoc
     */
    public function peekBegin()
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this[$this->beginIndex];
    }

    /**
     * @inheritDoc
     */
    public function peekEnd()
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this[$this->endIndex - 1];
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(): bool
    {
        return count($this->storage) === 0;
    }
}
