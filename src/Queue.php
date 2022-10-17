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
     * @inheritDoc
     */
    public function exchangeArray(array $input): void
    {
        parent::exchangeArray(array_values($input));
    }

    /**
     * @inheritDoc
     */
    public function addBegin($value): bool
    {
        array_unshift($this->storage, $value);

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

        return array_shift($this->storage);
    }

    /**
     * @inheritDoc
     */
    public function pollEnd()
    {
        if ($this->isEmpty()) {
            return null;
        }

        return array_pop($this->storage);
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

        return reset($this->storage);
    }

    /**
     * @inheritDoc
     */
    public function peekEnd()
    {
        if ($this->isEmpty()) {
            return null;
        }

        return end($this->storage);
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(): bool
    {
        return count($this->storage) === 0;
    }
}
