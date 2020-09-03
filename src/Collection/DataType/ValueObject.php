<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

use ArrayIterator;

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
     * @var array
     */
    protected $modelKeys = [];

    /**
     * @inheritDoc
     */
    public function __construct($input = null, $flags = 0, $iteratorClass = ArrayIterator::class)
    {
        parent::__construct([], $flags, $iteratorClass);
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
        foreach ((array) $input as $key => $value) {
            $this->offsetSet($key, $value);
        }

        return $this;
    }

    /**
     * Возвращает массив с значениями по умолчанию
     *
     * @return string[]
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
        if (method_exists($this, $func)) {
            $this->$func($value);

            return;
        }
        $this->modelSet($key, $value);
    }

    /**
     * Возвращает название функции на основе ключа для set
     *
     * @return string
     */
    protected function getFuncNameOfSetter(string $key)
    {
        return static::$modelSetPrefix . static::classify((string) $key);
    }

    /**
     * Обертка для метода ArrayObject::offsetSet
     *
     * @param string $key   ключ значения
     * @param mixed  $value значение
     *
     * @return void
     */
    protected function modelSet(string $key, $value)
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
            $data[$key] = $this->offsetGet($key);
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($key)
    {
        $func = static::getFuncNameOfGetter($key);
        if (method_exists($this, $func)) {
            return $this->$func();
        }

        return $this->modelGet($key);
    }

    /**
     * Возвращает название функции на основе ключа для get
     *
     * @return string
     */
    protected function getFuncNameOfGetter(string $key)
    {
        return static::$modelGetPrefix . static::classify((string) $key);
    }

    /**
     * Обертка для метода ArrayObject::offsetGet
     *
     * @param string $key ключ значения
     *
     * @return mixed
     */
    protected function modelGet(string $key)
    {
        return ArrayObject::offsetGet($key);
    }

    /**
     * @inheritDoc
     */
    public function getClone()
    {
        return new static($this->toArray());
    }

    /**
     * Преобразует строку из ("string_helper" или "string.helper" или "string-helper") в "StringHelper"
     *
     * @param string $value значение для преобразования
     */
    private static function classify(string $value, string $delimiter = ''): string
    {
        return trim(preg_replace_callback('/(^|_|\.|\-|\/)([a-z ]+)/im', function ($matches) use ($delimiter) {
            return ucfirst(mb_strtolower($matches[2])) . $delimiter;
        }, $value . ' '), ' ' . $delimiter);
    }
}
