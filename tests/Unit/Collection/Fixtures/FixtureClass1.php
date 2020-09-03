<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection\Fixtures;

/**
 * Класс для тестирования
 */
class FixtureClass1
{
    /**
     * @var mixed|null
     */
    public $value = null;

    /**
     * Конструктор
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Возвращает значение
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
