<?php

declare(strict_types=1);

namespace Fi1a\Unit\Collection\DataType;

use Fi1a\Collection\DataType\ArrayObject;
use Fi1a\Collection\DataType\PathAccess;
use PHPUnit\Framework\TestCase;

use const FILTER_FLAG_ALLOW_OCTAL;
use const FILTER_NULL_ON_FAILURE;
use const FILTER_VALIDATE_BOOLEAN;
use const FILTER_VALIDATE_INT;

/**
 * Тестирование объекта с доступом по пути
 */
class PathAccessTest extends TestCase
{
    /**
     * Провайдер данных для testGet
     *
     * @return mixed[]
     */
    public function dataProviderGet(): array
    {
        $data = [
            'key1' => 1,
            'key2' => 'string',
            'key3' => null,
            'key4' => [
                'key1' => 1,
                'key2' => 'string',
                'key3' => null,
            ],
        ];

        return [
            [
                $data,
                'key1',
                null,
                1,
            ],
            [
                $data,
                'key2',
                null,
                'string',
            ],
            [
                $data,
                'key3',
                null,
                null,
            ],
            [
                $data,
                'key4:key1',
                null,
                1,
            ],
            [
                $data,
                'key4:not_exist',
                null,
                null,
            ],
            [
                $data,
                'key4:not_exist',
                'default',
                'default',
            ],
            [
                $data,
                'key4:key1:not_exist',
                'default',
                'default',
            ],
            [
                $data,
                '',
                null,
                null,
            ],
        ];
    }

    /**
     * Возвращает значение используя путь
     *
     * @param mixed[] $data
     * @param string $path путь
     * @param mixed $default
     * @param mixed $equal
     *
     * @dataProvider dataProviderGet
     */
    public function testGet(array $data, string $path, $default, $equal): void
    {
        $this->assertEquals($equal, (new PathAccess($data))->get($path, $default));
    }

    /**
     * Провайдер данных для testSet
     *
     * @return mixed[]
     */
    public function dataProviderSet(): array
    {
        $array = new ArrayObject([1, 2, 3,]);

        return [
            [
                [],
                'key1',
                1,
                ['key1' => 1,],
            ],
            [
                ['key1' => 1,],
                'key1:key2',
                1,
                ['key1' => ['key2' => 1,],],
            ],
            [
                ['key1' => ['key2' => 1,],],
                '0',
                $array,
                ['key1' => ['key2' => 1,], 0 => $array,],
            ],
        ];
    }

    /**
     * Устанавливает значение используя путь
     *
     * @param mixed[] $data
     * @param string $path путь
     * @param mixed $value
     * @param mixed $equal
     *
     * @dataProvider dataProviderSet
     */
    public function testSet(array $data, string $path, $value, $equal): void
    {
        $this->assertEquals($equal, (new PathAccess($data))->set((string) $path, $value)->getArrayCopy());
    }

    /**
     * Провайдер данных для testDelete
     *
     * @return mixed[]
     */
    public function dataProviderDelete(): array
    {
        $data = [
            'key1' => 1,
            'key2' => 'string',
            'key3' => null,
            'key4' => [
                'key1' => 1,
                'key2' => 'string',
                'key3' => null,
            ],
        ];

        return [
            [
                $data,
                'key1',
                [
                    'key2' => 'string',
                    'key3' => null,
                    'key4' => [
                        'key1' => 1,
                        'key2' => 'string',
                        'key3' => null,
                    ],
                ],
            ],
            [
                $data,
                'key3',
                [
                    'key1' => 1,
                    'key2' => 'string',
                    'key4' => [
                        'key1' => 1,
                        'key2' => 'string',
                        'key3' => null,
                    ],
                ],
            ],
            [
                $data,
                'key4:key1',
                [
                    'key1' => 1,
                    'key2' => 'string',
                    'key3' => null,
                    'key4' => [
                        'key2' => 'string',
                        'key3' => null,
                    ],
                ],
            ],
            [
                $data,
                'key4:key5',
                [
                    'key1' => 1,
                    'key2' => 'string',
                    'key3' => null,
                    'key4' => [
                        'key1' => 1,
                        'key2' => 'string',
                        'key3' => null,
                    ],
                ],
            ],
            [
                $data,
                'key4:key5:key6',
                [
                    'key1' => 1,
                    'key2' => 'string',
                    'key3' => null,
                    'key4' => [
                        'key1' => 1,
                        'key2' => 'string',
                        'key3' => null,
                    ],
                ],
            ],
        ];
    }

    /**
     * Удаление
     *
     * @param mixed[] $data
     * @param string $path путь
     * @param mixed $equal
     *
     * @dataProvider dataProviderDelete
     */
    public function testDelete(array $data, string $path, $equal): void
    {
        $this->assertEquals($equal, (new PathAccess($data))->delete($path)->getArrayCopy());
    }

    /**
     * Провайдер данных для теста testHas
     *
     * @return mixed[]
     */
    public function dataProviderHas(): array
    {
        return [
            [
                ['key1' => ['key2' => 1]],
                'key1:key2',
                true,
            ],
            [
                ['key1' => ['key2' => 1]],
                'key1',
                true,
            ],
            [
                ['key1' => ['key2' => 1]],
                'key1:not_exist',
                false,
            ],
            [
                ['key1' => ['key2' => 1]],
                'key1:key2:not_exist',
                false,
            ],
        ];
    }

    /**
     * Проверка наличия значения
     *
     * @param mixed[] $data
     * @param string  $path путь
     * @param mixed   $equal
     *
     * @dataProvider dataProviderHas
     */
    public function testHas(array $data, string $path, $equal): void
    {
        $this->assertEquals($equal, (new PathAccess($data))->has($path));
    }

    /**
     * Провайдер данных для теста testGetBool
     *
     * @return mixed[]
     */
    public function dataProviderGetBool(): array
    {
        return [
            [
                ['key' => 'true'],
                true,
            ],
            [
                ['key' => 'false'],
                false,
            ],
            [
                ['key' => null],
                null,
            ],
            [
                ['key' => ''],
                null,
            ],
            [
                ['key' => 'some value'],
                true,
            ],
        ];
    }

    /**
     * Тестирование возврата значения преобразованного к bool
     *
     * @param mixed[] $data
     * @param mixed $equal
     *
     * @dataProvider dataProviderGetBool
     */
    public function testGetBool(array $data, $equal): void
    {
        $this->assertEquals($equal, (new PathAccess($data))->getBool('key'));
    }

    /**
     * Провайдер данных для теста testGetInt
     *
     * @return mixed[]
     */
    public function dataProviderGetInt(): array
    {
        return [
            [
                ['key' => '1'],
                1,
            ],
            [
                ['key' => '0'],
                0,
            ],
            [
                ['key' => '-1'],
                -1,
            ],
            [
                ['key' => null],
                null,
            ],
            [
                ['key' => ''],
                null,
            ],
        ];
    }

    /**
     * Тестирование возврата значения преобразованного к int
     *
     * @param mixed[] $data
     * @param mixed $equal
     *
     * @dataProvider dataProviderGetInt
     */
    public function testGetInt(array $data, $equal): void
    {
        $this->assertEquals($equal, (new PathAccess($data))->getInt('key'));
    }

    /**
     * Провайдер данных для теста testFilter
     *
     * @return mixed[]
     */
    public function dataProviderFilter(): array
    {
        return [
            [
                ['key' => '0755'],
                FILTER_VALIDATE_INT,
                [
                    'options' => [
                        'default' => 3,
                        'min_range' => 0,
                    ],
                    'flags' => FILTER_FLAG_ALLOW_OCTAL,
                ],
                0755,
            ],
            [
                ['key' => 'oops'],
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE,
                null,
            ],
        ];
    }

    /**
     * Фильтрация значений
     *
     * @param mixed[] $data
     * @param int $filter параметр фильтра
     * @param mixed $options
     * @param mixed $equal
     *
     * @dataProvider dataProviderFilter
     */
    public function testFilter(array $data, int $filter, $options, $equal): void
    {
        $this->assertEquals($equal, (new PathAccess($data))->filter('key', null, $filter, $options));
    }
}
