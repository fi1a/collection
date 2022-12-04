<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

/**
 * Интерфейс модели вызова set & get методов класса при обращении к ключам массива
 */
interface ValueObjectInterface extends ArrayObjectInterface
{
    /**
     * @param mixed[] $input массив со значениями
     */
    public static function __set_state(array $input): ValueObjectInterface;

    /**
     * Инициализация из массива
     *
     * @param mixed[]|ArrayObjectInterface $input массив для инициализации
     */
    public function fromArray($input): ValueObjectInterface;

    /**
     * Возвращает массив с вызовом set & get методов
     *
     * @return mixed[]
     */
    public function toArray(): array;
}
