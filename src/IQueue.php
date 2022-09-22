<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\DataType\IArrayObject;

/**
 * Интерфейс очереди
 */
interface IQueue extends IArrayObject
{
    /**
     * Добавить в начало очереди
     *
     * @param mixed $value
     */
    public function addBegin($value): bool;

    /**
     * Добавить в конец очереди
     *
     * @param mixed $value
     */
    public function addEnd($value): bool;

    /**
     * Возвращете и удаляет элемент из начала очереди.
     * Выбрасывает исключение, если очередь пуста.
     *
     * @return mixed
     */
    public function removeBegin();

    /**
     * Возвращете и удаляет элемент из конца очереди.
     * Выбрасывает исключение, если очередь пуста.
     *
     * @return mixed
     */
    public function removeEnd();

    /**
     * Возвращет и удаляет элемент из начала очереди
     *
     * @return mixed
     */
    public function pollBegin();

    /**
     * Возвращет и удаляет элемент из конца очереди
     *
     * @return mixed
     */
    public function pollEnd();

    /**
     * Возвращает, но не удаляет элемент из начала очереди.
     * Выбрасывает исключение, если очередь пуста.
     *
     * @return mixed
     */
    public function beginElement();

    /**
     * Возвращает, но не удаляет элемент из конца очереди.
     * Выбрасывает исключение, если очередь пуста.
     *
     * @return mixed
     */
    public function endElement();

    /**
     * Возвращает, но не удаляет элемент из начала очереди
     *
     * @return mixed
     */
    public function peekBegin();

    /**
     * Возвращает, но не удаляет элемент из конца очереди
     *
     * @return mixed
     */
    public function peekEnd();

    /**
     * Определяет пустая очередь или нет
     */
    public function isEmpty(): bool;
}
