<?php

declare(strict_types=1);

namespace Fi1a\Collection;

/**
 * Методы типизации значений
 */
trait TTypedValue
{
    /**
     * Сравнивает переданный тип значения с реальным
     *
     * @param mixed $value
     */
    protected function checkValueType(string $type, $value): bool
    {
        switch ($type) {
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
}
