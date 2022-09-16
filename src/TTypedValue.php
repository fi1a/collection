<?php

declare(strict_types=1);

namespace Fi1a\Collection;

use Fi1a\Collection\Exception\InvalidArgumentException;

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
                'Value ' . $this->convert($value) . ' is not type ' . $this->getType()
            );
        }
    }

    /**
     * Конвертирует значение в строку
     *
     * @param mixed $value
     */
    private function convert($value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if (is_null($value)) {
            return 'null';
        }
        if (is_array($value)) {
            return 'array';
        }
        if (is_object($value) && !method_exists($value, '__toString')) {
            return get_class($value);
        }
        if ($value === 0) {
            return '0';
        }

        return (string) $value;
    }
}
