<?php

declare(strict_types=1);

namespace Fi1a\Collection;

/**
 * Очередь с типизацией значений
 */
interface ITypedValueQueue extends IQueue
{
    /**
     * Конструктор
     *
     * @param mixed[] $queue
     */
    public function __construct(string $type, array $queue = []);

    /**
     * Возвращает объявленный тип значений
     */
    public function getType(): string;

    /**
     * Добавить в начало очереди, без исключения при проверке типа значения
     *
     * @param mixed $value
     */
    public function offerBegin($value): bool;

    /**
     * Добавить в конец очереди, без исключения при проверке типа значения
     *
     * @param mixed $value
     */
    public function offerEnd($value): bool;
}
