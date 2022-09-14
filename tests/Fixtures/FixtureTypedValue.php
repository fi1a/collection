<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection\Fixtures;

use Fi1a\Collection\TTypedValue;

/**
 * Для тестирование методов типизации значений
 */
class FixtureTypedValue
{
    use TTypedValue;

    /**
     * @param mixed $value
     */
    public function validate(string $type, $value): bool
    {
        return $this->checkValueType($type, $value);
    }
}
