<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\Exception\InvalidArgumentException;
use Fi1a\Format\Formatter;

/**
 * Методы типизации значений
 */
trait TTypedValue
{
    /**
     * @var string
     */
    protected $type = 'mixed';

    /**
     * Возвращает объявленный тип значений
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Сравнивает переданный тип значения с реальным
     *
     * @param mixed $value
     */
    protected function checkValueType(string $type, $value): bool
    {
        switch ($type) {
            case 'mixed':
                return true;
            case 'array':
                return is_array($value);
            case 'boolean':
            case 'bool':
                return is_bool($value);
            case 'callable':
                return is_callable($value);
            case 'int':
            case 'integer':
                return is_int($value);
            case 'float':
            case 'double':
                return is_float($value);
            case 'numeric':
                return is_numeric($value);
            case 'string':
                return is_string($value);
            case 'resource':
                return is_resource($value);
            case 'scalar':
                return is_scalar($value);
            case 'object':
                return is_object($value);
            default:
                return $value instanceof $type;
        }
    }

    /**
     * Валидация типа значения. Если тип не совпадает с объявленным, выбрасывает исключение.
     *
     * @param mixed $value
     */
    protected function validateType($value): void
    {
        if (!$this->checkValueType($this->getType(), $value)) {
            throw new InvalidArgumentException(
                Formatter::format('Value {{0}} not is type {{1}}', [$value, $this->getType()])
            );
        }
    }
}
