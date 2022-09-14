<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection;

use Fi1a\Unit\Collection\Fixtures\FixtureTypedValue;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Тестирование методов типизации значений
 */
class TTypedValueTest extends TestCase
{
    /**
     * Провайдер данных для теста testConstruct
     *
     * @return mixed[]
     */
    public function dataCheckValueType(): array
    {
        return [
            [true, 'array', [1, 2, 3]],
            [true, 'bool', true],
            [false, 'callable', 1],
            [true, 'integer', 1],
            [true, 'float', 1.2],
            [true, 'numeric', 1.2],
            [true, 'string', '1.2'],
            [false, 'resource', '1.2'],
            [true, 'scalar', '1.2'],
            [true, 'object', new stdClass()],
            [true, stdClass::class, new stdClass()],
        ];
    }

    /**
     * Тестирование
     *
     * @param mixed $value
     *
     * @dataProvider dataCheckValueType
     */
    public function testCheckValueType(bool $expected, string $type, $value): void
    {
        $instance = new FixtureTypedValue();
        $this->assertEquals($expected, $instance->validate($type, $value));
    }
}
