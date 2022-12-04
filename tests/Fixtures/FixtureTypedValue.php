<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection\Fixtures;

use Fi1a\Collection\TypedValueTrait;

/**
 * Для тестирование методов типизации значений
 */
class FixtureTypedValue
{
    use TypedValueTrait;

    /**
     * @param mixed $value
     */
    public function validate(string $type, $value): bool
    {
        return $this->checkValueType($type, $value);
    }

    /**
     * @param mixed $value
     */
    public function validateTypeTest(string $type, $value): void
    {
        $this->type = $type;
        $this->validateType($value);
    }
}
