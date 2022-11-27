<?php

declare(strict_types=1);

namespace Fi1a\Collection\DataType;

use Fi1a\Collection\Helpers\ArrayHelper;

/**
 * Реализует интерфейсы \ArrayAccess, \Countable
 */
trait TMapArrayObject
{
    /**
     * Определяет пустой массив или нет
     */
    public function isEmpty(): bool
    {
        return ArrayHelper::isEmpty($this->storage);
    }

    /**
     * Возвращает первый элемент
     *
     * @return mixed
     */
    public function first()
    {
        return ArrayHelper::first($this->storage);
    }

    /**
     * Возвращает последний элемент
     *
     * @return mixed
     */
    public function last()
    {
        return ArrayHelper::last($this->storage);
    }

    /**
     * Очистить массив значений
     *
     * @return static
     */
    public function clear()
    {
        $this->storage = [];

        return $this;
    }

    /**
     * Проверяет, присутствует ли в массиве указанное значение
     *
     * @param mixed $value
     */
    public function hasValue($value, bool $strict = true): bool
    {
        return ArrayHelper::hasValue($this->storage, $value, $strict);
    }

    /**
     * Возвращает ключи массива
     *
     * @return mixed[]
     */
    public function keys(): array
    {
        return ArrayHelper::keys($this->storage);
    }

    /**
     * Есть ли элемент с таким ключем
     *
     * @param string|int|null $key ключ
     */
    public function has($key): bool
    {
        return ArrayHelper::has($this->storage, $key);
    }

    /**
     * Возвращает элемент по ключу
     *
     * @param string|int|null $key ключ
     * @param mixed $default значение по умолчанию, возвращается при отсутствии ключа
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return ArrayHelper::get($this->storage, $key, $default);
    }

    /**
     * Устанавливает значение по ключу
     *
     * @param string|int|null $key ключ
     * @param mixed $value устанавливаемое значение
     *
     * @return static
     */
    public function set($key, $value)
    {
        $this->storage = ArrayHelper::set($this->storage, $key, $value);

        return $this;
    }

    /**
     * Удаляет элемент по ключу, возвращает удаленное значение
     *
     * @param string|int|null $key ключ
     *
     * @return mixed
     */
    public function delete($key)
    {
        return ArrayHelper::delete($this->storage, $key);
    }

    /**
     * Удаляет элемент по ключу, если значение равно переданному. Если элемент удален, возвращает true.
     *
     * @param string|int|null $key ключ
     * @param mixed $value
     */
    public function deleteIf($key, $value): bool
    {
        return ArrayHelper::deleteIf($this->storage, $key, $value);
    }

    /**
     * Устанавливает значение по ключу, если значение уже есть в массиве, возвращает его
     *
     * @param string|int|null $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function put($key, $value)
    {
        return ArrayHelper::put($this->storage, $key, $value);
    }

    /**
     * Устанавливает значение по ключу, если его нет. Возвращает предыдущее значение
     *
     * @param string|int|null $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function putIfAbsent($key, $value)
    {
        return ArrayHelper::putIfAbsent($this->storage, $key, $value);
    }

    /**
     * Заменяет значение элемента по ключу, только если есть значение. Возвращает предыдущее значение
     *
     * @param string|int|null $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function replace($key, $value)
    {
        return ArrayHelper::replace($this->storage, $key, $value);
    }

    /**
     * Заменяет значение элемента по ключу, только если текущее значение равно $oldValue.
     * Если элемент заменен, возвращает true.
     *
     * @param string|int|null $key
     * @param mixed $oldValue
     * @param mixed $newValue
     */
    public function replaceIf($key, $oldValue, $newValue): bool
    {
        return ArrayHelper::replaceIf($this->storage, $key, $oldValue, $newValue);
    }

    /**
     * Добавить в коллекцию значение
     *
     * @param mixed $value значение
     *
     * @return static
     */
    public function add($value)
    {
        /**
         * @var mixed
         */
        $this[] = $value;

        return $this;
    }

    /**
     * Вызывает функцию, передавая ключ и значение из коллекции
     *
     * @param callable(mixed, mixed):void $callback функция, принимающая ключ и значение из коллекции
     *
     * @return $this
     */
    public function each(callable $callback)
    {
        ArrayHelper::each($this->storage, $callback);

        return $this;
    }

    /**
     * Вызывает функцию, передавая ключ и значение из коллекции и заменяет элемент результатом
     *
     * @param callable(mixed, mixed):mixed $callback функция, принимающая ключ и значение из коллекции
     *
     * @return $this
     */
    public function map(callable $callback)
    {
        $this->storage = ArrayHelper::map($this->storage, $callback);

        return $this;
    }

    /**
     * Возвращает значения переданного ключа, свойства или метода
     *
     * @param string $name ключ, свойство или метод
     *
     * @return mixed[]
     */
    public function column(string $name): array
    {
        return ArrayHelper::column($this->storage, $name);
    }

    /**
     * Сортировка элементов коллекции по значениям переданного ключа, свойства или метода
     *
     * @param string $name ключ, свойство или метод
     * @param string $order направление сортировки
     *
     * @return static
     */
    public function sort(string $name, string $order = self::SORT_ASC)
    {
        $values = ArrayHelper::sort($this->storage, $name, $order);
        $collection = clone $this;
        $collection->exchangeArray($values);

        return $collection;
    }

    /**
     * Возвращает отфильтрованную коллекцию
     *
     * @param callable(mixed, mixed=):scalar $callback функция для фильтрации
     *
     * @return static
     */
    public function filter(callable $callback)
    {
        $collection = clone $this;
        $collection->exchangeArray(ArrayHelper::filter($this->storage, $callback));

        return $collection;
    }

    /**
     * Возвразает коллекцию с элементами у которых значение ключа, свойства или метода равно переданному значению
     *
     * @param string $name ключ, свойство или метод
     * @param mixed $value значение для сравнения
     *
     * @return static
     */
    public function where(string $name, $value)
    {
        $collection = clone $this;
        $collection->exchangeArray(ArrayHelper::where($this->storage, $name, $value));

        return $collection;
    }

    /**
     * Возвращает новую коллекцию с расходящимися элементами текущей коллекции с переданной
     *
     * @param IArrayObject|mixed[] $collection коллекция для вычисления расхождения
     *
     * @return static
     */
    public function diff($collection)
    {
        $cloneCollection = clone $this;
        $cloneCollection->exchangeArray(
            ArrayHelper::diff(
                $this->storage,
                $collection instanceof IArrayObject ? $collection->getArrayCopy() : $collection
            )
        );

        return $cloneCollection;
    }

    /**
     * Возвращает новую коллекцию с пересечением элементов текущей коллекции с переданной
     *
     * @param IArrayObject|mixed[] $collection коллекция для вычисления пересечения
     *
     * @return static
     */
    public function intersect($collection)
    {
        $cloneCollection = clone $this;
        $cloneCollection->exchangeArray(
            ArrayHelper::intersect(
                $this->storage,
                $collection instanceof IArrayObject ? $collection->getArrayCopy() : $collection
            )
        );

        return $cloneCollection;
    }

    /**
     * Объединяет элементы текущей коллекции с элементами переданной и возвращает новую коллекцию
     *
     * @param IArrayObject|mixed[] $collection коллекция для объединения
     *
     * @return static
     */
    public function merge($collection)
    {
        $cloneCollection = clone $this;
        $cloneCollection->exchangeArray(
            array_merge(
                $this->storage,
                $collection instanceof IArrayObject ? $collection->getArrayCopy() : $collection
            )
        );

        return $cloneCollection;
    }

    /**
     * Сбросить ключи коллекции
     *
     * @return $this
     */
    public function resetKeys()
    {
        $this->storage = ArrayHelper::resetKeys($this->storage);

        return $this;
    }

    /**
     * Итеративно уменьшает коллекцию к единственному значению, используя callback-функцию
     *
     * @param callable(mixed, mixed):mixed $callback
     * @param mixed    $initial
     *
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        return ArrayHelper::reduce($this->storage, $callback, $initial);
    }

    /**
     * Итеративно уменьшает коллекцию к единственному значению  в обратном порядке, используя callback-функцию
     *
     * @param callable(mixed, mixed):mixed $callback
     * @param mixed $initial
     *
     * @return mixed
     */
    public function reduceRight(callable $callback, $initial = null)
    {
        return ArrayHelper::reduceRight($this->storage, $callback, $initial);
    }

    /**
     * Оборачивает значения и возвращает новую коллекцию
     *
     * @return static
     */
    public function wraps(string $prefix, ?string $suffix = null)
    {
        $collection = clone $this;
        $collection->exchangeArray(
            ArrayHelper::wraps($collection->getArrayCopy(), $prefix, $suffix)
        );

        return $collection;
    }

    /**
     * Объединяет элементы в строку
     */
    public function join(string $separator): string
    {
        /** @psalm-suppress MixedArgumentTypeCoercion */
        return ArrayHelper::join($this->storage, $separator);
    }

    /**
     * Вставить значения
     *
     * @param mixed[] $values
     *
     * @return $this
     */
    public function insert(int $index, array $values)
    {
        $this->storage = ArrayHelper::insert($this->storage, $index, $values);

        return $this;
    }

    /**
     * Возвращает ключ первого элемента
     *
     * @return string|int|false
     */
    public function firstKey()
    {
        return ArrayHelper::firstKey($this->storage);
    }

    /**
     * Возвращает ключ последнего элемента
     *
     * @return string|int|false
     */
    public function lastKey()
    {
        return ArrayHelper::lastKey($this->storage);
    }

    /**
     * Переключает значения
     *
     * @param string|int|null $key
     * @param mixed           $firstValue
     * @param mixed           $secondValue
     *
     * @return $this
     */
    public function toggleValue($key, $firstValue, $secondValue)
    {
        $this->storage = ArrayHelper::toggleValue($this->storage, $key, $firstValue, $secondValue);

        return $this;
    }

    /**
     * Возвращает true, если все элементы удовлетворяют условию
     *
     * @param callable(mixed, string|int): bool $condition
     */
    public function every(callable $condition): bool
    {
        return ArrayHelper::every($this->storage, $condition);
    }

    /**
     * Возвращает коллекцию без элементов удовлетворяющих условию
     *
     * @param callable(mixed, string|int): bool $condition
     *
     * @return static
     */
    public function without(callable $condition)
    {
        $collection = clone $this;
        $collection->exchangeArray(ArrayHelper::without($this->storage, $condition));

        return $collection;
    }

    /**
     * Возвращает коллекцию с элементами удовлетворяющими условию
     *
     * @param callable(mixed, string|int): bool $condition
     *
     * @return static
     */
    public function with(callable $condition)
    {
        $collection = clone $this;
        $collection->exchangeArray(ArrayHelper::with($this->storage, $condition));

        return $collection;
    }

    /**
     * Возвращает коллекцию, опуская заданное количество элементов с начала
     *
     * @return static
     */
    public function drop(int $count)
    {
        $collection = clone $this;
        $collection->exchangeArray(ArrayHelper::drop($this->storage, $count));

        return $collection;
    }

    /**
     * Возвращает коллекцию, опуская заданное количество элементов с конца
     *
     * @return static
     */
    public function dropRight(int $count)
    {
        $collection = clone $this;
        $collection->exchangeArray(ArrayHelper::dropRight($this->storage, $count));

        return $collection;
    }

    /**
     * Возвращает первый элемент, который удовлетворяет условию $condition,
     * возвращает false, если такого элемента не существует
     *
     * @param callable(mixed, string|int): bool $condition
     *
     * @return mixed
     */
    public function findValue(callable $condition)
    {
        return ArrayHelper::findValue($this->storage, $condition);
    }

    /**
     * Возвращает последний элемент, который удовлетворяет условию $condition,
     * возвращает false, если такого элемента не существует
     *
     * @param callable(mixed, string|int): bool $condition
     *
     * @return mixed
     */
    public function findLastValue(callable $condition)
    {
        return ArrayHelper::findLastValue($this->storage, $condition);
    }

    /**
     * Возвращает первый ключ элемента, который удовлетворяет условию $condition,
     * возвращает false, если такого элемента не существует
     *
     * @param callable(mixed, string|int): bool $condition
     *
     * @return mixed
     */
    public function findKey(callable $condition)
    {
        return ArrayHelper::findKey($this->storage, $condition);
    }

    /**
     * Возвращает последний ключ элемента, который удовлетворяет условию $condition,
     * возвращает false, если такого элемента не существует
     *
     * @param callable(mixed, string|int): bool $condition
     *
     * @return mixed
     */
    public function findLastKey(callable $condition)
    {
        return ArrayHelper::findLastKey($this->storage, $condition);
    }

    /**
     * @deprecated
     *
     * Проверяет, присутствует ли в коллекции значение
     *
     * @param mixed $value значение
     * @param bool $strict если true, также проверяет типы значений
     */
    public function contains($value, bool $strict = true): bool
    {
        return $this->hasValue($value, $strict);
    }

    /**
     * Возвращает новый массив с переданным ключем и колонкой
     *
     * @param string|int $map
     * @param string|int|null $column
     *
     * @return static
     */
    public function mapAndColumn($map, $column = null)
    {
        $collection = clone $this;
        $collection->exchangeArray(ArrayHelper::mapAndColumn($this->storage, $map, $column));

        return $collection;
    }
}
