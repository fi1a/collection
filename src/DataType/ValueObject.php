<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

/**
 * Класс модели вызова set & get методов при обращении к ключам массива
 */
class ValueObject extends ArrayObject implements IValueObject
{
    /**
     * @var string
     */
    protected static $modelGetPrefix = 'get';

    /**
     * @var string
     */
    protected static $modelSetPrefix = 'set';

    /**
     * @var string[]
     */
    protected $modelKeys = [];

    /**
     * @inheritDoc
     */
    final public function __construct(?array $input = null)
    {
        parent::__construct([]);
        $this->fromArray((array) $input);
    }

    /**
     * @inheritDoc
     */
    public function fromArray($input): IValueObject
    {
        $default = $this->getDefaultModelValues();
        $this->exchangeArray($default);
        $input = array_merge($default, (array) $input);
        /**
         * @var mixed $value
         */
        foreach ($input as $key => $value) {
            $this->offsetSet($key, $value);
        }

        return $this;
    }

    /**
     * Возвращает массив со значениями по умолчанию
     *
     * @return null[]
     */
    protected function getDefaultModelValues()
    {
        return array_fill_keys($this->modelKeys, null);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($key, $value)
    {
        $func = static::getFuncNameOfSetter($key);
        if ($func && method_exists($this, $func)) {
            $this->$func($value);

            return;
        }
        $this->modelSet($key, $value);
    }

    /**
     * Возвращает название функции на основе ключа для set
     *
     * @param int|string|null $key
     *
     * @return string|null
     */
    protected function getFuncNameOfSetter($key)
    {
        return static::$modelSetPrefix . static::classify((string) $key);
    }

    /**
     * Обертка для метода ArrayObject::offsetSet
     *
     * @param string|int|null $key   ключ значения
     * @param mixed  $value значение
     *
     * @return void
     */
    protected function modelSet($key, $value)
    {
        ArrayObject::offsetSet($key, $value);
    }

    /**
     * @inheritDoc
     */
    public static function __set_state(array $input): IValueObject
    {
        return new static($input);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $data = [];
        $keys = array_keys($this->getArrayCopy());
        foreach ($keys as $key) {
            /**
             * @var mixed
             */
            $data[$key] = $this->offsetGet($key);
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function &offsetGet($offset)
    {
        $func = static::getFuncNameOfGetter($offset);
        if (method_exists($this, $func)) {
            /**
             * @var mixed
             */
            $value = $this->$func();
        } else {
            /**
             * @var mixed
             */
            $value = &$this->modelGet($offset);
        }

        return $value;
    }

    /**
     * Возвращает название функции на основе ключа для get
     *
     * @param string|int $key
     */
    protected function getFuncNameOfGetter($key): string
    {
        return static::$modelGetPrefix . static::classify((string) $key);
    }

    /**
     * Обертка для метода ArrayObject::offsetGet
     *
     * @param string|int $key ключ значения
     *
     * @return mixed
     */
    protected function &modelGet($key)
    {
        /**
         * @psalm-suppress UnusedVariable
         * @psalm-suppress MixedAssignment
         */
        $value = &ArrayObject::offsetGet($key);

        return $value;
    }

    /**
     * Преобразует строку из ("string_helper" или "string.helper" или "string-helper") в "StringHelper"
     *
     * @param string $value значение для преобразования
     */
    private static function classify(string $value, string $delimiter = ''): string
    {
        return trim(
            preg_replace_callback('/(^|_|\.|\-|\/)([a-z ]+)/im', function (array $matches) use ($delimiter) {
                return ucfirst(mb_strtolower($matches[2])) . $delimiter;
            }, $value . ' '),
            ' ' . $delimiter
        );
    }
}
