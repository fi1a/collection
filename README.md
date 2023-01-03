# Структуры данных и коллекции в PHP

[![Latest Version][badge-release]][packagist]
[![Software License][badge-license]][license]
[![PHP Version][badge-php]][php]
![Coverage Status][badge-coverage]
[![Total Downloads][badge-downloads]][downloads]
[![Support mail][badge-mail]][mail]

Пакет fi1a/collection предоставляет классы структур данных и коллекций.

## Установка

Установить этот пакет можно как зависимость, используя Composer.

``` bash
composer require fi1a/collection
```

## Использование

### Типы данных

#### Объект Fi1a\Collection\DataType\ArrayObject, работающий также как и массив

Реализует интерфейс `Fi1a\Collection\DataType\ValueObjectInterface`.

```php
use Fi1a\Collection\DataType\ArrayObject;

$arrayObject = new ArrayObject(['foo', 'bar', 'baz',]);

foreach ($arrayObject as $value) {
    $value; // 'foo', 'bar', 'baz'
}

count($arrayObject); // 3
```

#### Объект Fi1a\Collection\DataType\MapArrayObject, работающий также как и массив

Реализует интерфейс `Fi1a\Collection\DataType\MapArrayObjectInterface` с методами расширяющими использование массивов.

```php
use Fi1a\Collection\DataType\MapArrayObject;

$arrayObject = new MapArrayObject();

$arrayObject->add('foo');
$arrayObject->add('bar');
$arrayObject->add('baz');

foreach ($arrayObject as $value) {
    $value; // 'foo', 'bar', 'baz'
}

$arrayObject->count(); // 3
```

Доступные методы:

| Метод                                              | Описание                                                                                                                           |
|----------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------------|
| isEmpty(): bool                                    | Определяет пустой массив или нет                                                                                                   |
| first()                                            | Возвращает первый элемент                                                                                                          |
| last()                                             | Возвращает последний элемент                                                                                                       |
| clear()                                            | Очистить массив значений                                                                                                           |
| hasValue($value): bool                             | Проверяет, присутствует ли в массиве указанное значение                                                                            |
| keys()                                             | Возвращает ключи массива                                                                                                           |
| has($key)                                          | Есть ли элемент с таким ключем                                                                                                     |
| get($key, $default = null)                         | Возвращает элемент по ключу                                                                                                        |
| set($key, $value)                                  | Устанавливает значение по ключу                                                                                                    |
| delete($key)                                       | Удаляет элемент по ключу, возвращает удаленное значение                                                                            |
| deleteIf($key, $value): bool                       | Удаляет элемент по ключу, если значение равно переданному. Если элемент удален, возвращает true.                                   |
| put($key, $value)                                  | Устанавливает значение по ключу, если значение уже есть в массиве, возвращает его                                                  |
| putIfAbsent($key, $value)                          | Устанавливает значение по ключу, если его нет. Возвращает предыдущее значение                                                      |
| replace($key, $value)                              | Заменяет значение элемента по ключу, только если есть значение. Возвращает предыдущее значение                                     |
| replaceIf($key, $oldValue, $newValue): bool        | Заменяет значение элемента по ключу, только если текущее значение равно $oldValue. Если элемент заменен, возвращает true.          |
| add($value)                                        | Добавить в коллекцию значение                                                                                                      |
| each(callable $callback)                           | Вызывает функцию, передавая ключ и значение из коллекции                                                                           |
| map(callable $callback)                            | Вызывает функцию, передавая ключ и значение из коллекции и заменяет элемент результатом                                            |
| column(string $name): array                        | Возвращает значения переданного ключа, свойства или метода                                                                         |
| sort(string $name, string $order = self::SORT_ASC) | Сортировка элементов коллекции по значениям переданного ключа, свойства или метода                                                 |
| filter(callable $callback)                         | Возвращает отфильтрованную коллекцию                                                                                               |
| where(string $name, $value)                        | Возвразает коллекцию с элементами у которых значение ключа, свойства или метода равно переданному значению                         |
| diff($collection)                                  | Возвращает новую коллекцию с расходящимися элементами текущей коллекции с переданной                                               |
| intersect($collection)                             | Возвращает новую коллекцию с пересечением элементов текущей коллекции с переданной                                                 |
| merge($collection)                                 | Объединяет элементы текущей коллекции с элементами переданной и возвращает новую коллекцию                                         |
| resetKeys()                                        | Сбросить ключи коллекции                                                                                                           |
| reduce(callable $callback, $initial = null)        | Итеративно уменьшает коллекцию к единственному значению, используя callback-функцию                                                |
| reduceRight(callable $callback, $initial = null)   | Итеративно уменьшает коллекцию к единственному значению  в обратном порядке, используя callback-функцию                            |
| wraps(string $prefix, ?string $suffix = null)      | Оборачивает значения и возвращает новую коллекцию                                                                                  |
| join(string $separator): string                    | Объединяет элементы в строку                                                                                                       |
| insert(int $index, array $values)                  | Вставить значения                                                                                                                  |
| firstKey()                                         | Возвращает ключ первого элемента                                                                                                   |
| lastKey()                                          | Возвращает ключ последнего элемента                                                                                                |
| toggleValue($key, $firstValue, $secondValue)       | Переключает значения                                                                                                               |
| every(callable $condition): bool                   | Возвращает true, если все элементы удовлетворяют условию                                                                           |
| without(callable $condition)                       | Возвращает коллекцию без элементов удовлетворяющих условию                                                                         |
| with(callable $condition)                          | Возвращает коллекцию с элементами удовлетворяющими условию                                                                         |
| drop(int $count)                                   | Возвращает коллекцию, опуская заданное количество элементов с начала                                                               |
| dropRight(int $count)                              | Возвращает коллекцию, опуская заданное количество элементов с конца                                                                |
| findValue(callable $condition)                     | Возвращает первый элемент, который удовлетворяет условию $condition, возвращает false, если такого элемента не существует          |
| findLastValue(callable $condition)                 | Возвращает последний элемент, который удовлетворяет условию $condition, возвращает false, если такого элемента не существует       |
| findKey(callable $condition)                       | Возвращает первый ключ элемента, который удовлетворяет условию $condition, возвращает false, если такого элемента не существует    |
| findLastKey(callable $condition)                   | Возвращает последний ключ элемента, который удовлетворяет условию $condition, возвращает false, если такого элемента не существует |
| mapAndColumn($map, $column = null)                 | Возвращает новый массив с переданным ключем и колонкой                                                                             |

#### Fi1a\Collection\DataType\PathAccess класс реализует доступ по пути к значениям

Реализует интерфейс `Fi1a\Collection\DataType\PathAccessInterface`. Данный класс позволяет получать доступ к ключам массива по пути (foo:bar:baz).

```php
use Fi1a\Collection\DataType\PathAccess;

$register = new PathAccess(['foo' => ['bar' => ['baz' => 1], 'qux' => 2,],]);

$register->get('foo:bar:baz'); // 1
$register->get('foo:qux'); // 2

$register->has('foo:bar'); // true
$register->has('foo:bar:baz'); // true
$register->has('foo:bar:baz:bat'); // false
```

#### Объект-значение Fi1a\Collection\DataType\ValueObject с методами set/get

Реализует интерфейс `Fi1a\Collection\DataType\ValueObjectInterface`. Предоставляет возможность задать set/get методы для значений и работает также как и массив.

```php
use Fi1a\Collection\DataType\ValueObject;

class Foo extends ValueObject
{
    public function getBar(): string
    {
        return (string) $this->modelGet('bar');
    }

    public function setBar(string $bar): self
    {
        $this->modelSet('bar', $bar . 'baz');

        return $this;
    }
}

$valueObject = new Foo();

$valueObject['bar'] = 'bar';

isset($valueObject['bar']); // true
$valueObject['bar']; // 'barbaz'
$valueObject->getBar(); // 'barbaz'
```

### Хелперы

#### Хелпер Fi1a\Collection\Helpers\ArrayHelper

Содержит вспомогательные методы для работы с массивом.

| Метод                                                                   | Описание                                                                                                                           |
|-------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------------|
| isEmpty(array $array): bool                                             | Определяет пустой массив или нет                                                                                                   |
| first(array $array)                                                     | Возвращает первый элемент                                                                                                          |
| last(array $array)                                                      | Возвращает последний элемент                                                                                                       |
| hasValue(array $array, $value, bool $strict = true): bool               | Проверяет, присутствует ли в массиве указанное значение                                                                            |
| keys(array $array): array                                               | Возвращает ключи массива                                                                                                           |
| has(array $array, $key): bool                                           | Есть ли элемент с таким ключем                                                                                                     |
| get(array $array, $key, $default = null)                                | Возвращает элемент по ключу                                                                                                        |
| set(array $array, $key, $value): array                                  | Устанавливает значение по ключу                                                                                                    |
| delete(array &$array, $key)                                             | Удаляет элемент по ключу, возвращает удаленное значение                                                                            |
| deleteIf(array &$array, $key, $value): bool                             | Удаляет элемент по ключу, если значение равно переданному. Если элемент удален, возвращает true.                                   |
| put(array &$array, $key, $value)                                        | Устанавливает значение по ключу, если значение уже есть в массиве, возвращает его                                                  |
| putIfAbsent(array &$array, $key, $value)                                | Устанавливает значение по ключу, если его нет. Возвращает предыдущее значение                                                      |
| replace(array &$array, $key, $value)                                    | Заменяет значение элемента по ключу, только если есть значение. Возвращает предыдущее значение                                     |
| replaceIf(array &$array, $key, $oldValue, $newValue): bool              | Заменяет значение элемента по ключу, только если текущее значение равно $oldValue. Если элемент заменен, возвращает true.          |
| each(array $array, callable $callback): void                            | Вызывает функцию, передавая ключ и значение из коллекции                                                                           |
| map(array $array, callable $callback): array                            | Вызывает функцию, передавая ключ и значение из коллекции и заменяет элемент результатом                                            |
| column(array $array, string $name): array                               | Возвращает значения переданного ключа, свойства или метода                                                                         |
| sort(array $array, string $name, string $order = self::SORT_ASC): array | Сортировка элементов коллекции по значениям переданного ключа, свойства или метода                                                 |
| filter(array $array, callable $callback): array                         | Возвращает отфильтрованную коллекцию                                                                                               |
| where(array $array, string $name, $value): array                        | Возвразает коллекцию с элементами у которых значение ключа, свойства или метода равно переданному значению                         |
| diff(array $array, array $collection): array                            | Возвращает новую коллекцию с расходящимися элементами текущей коллекции с переданной                                               |
| intersect(array $array, array $collection): array                       | Возвращает новую коллекцию с пересечением элементов текущей коллекции с переданной                                                 |
| resetKeys(array $array): array                                          | Сбросить ключи                                                                                                                     |
| reduce(array $array, callable $callback, $initial = null)               | Итеративно уменьшает коллекцию к единственному значению, используя callback-функцию                                                |
| reduceRight(array $array, callable $callback, $initial = null)          | Итеративно уменьшает коллекцию к единственному значению  в обратном порядке, используя callback-функцию                            |
| wraps(array $array, string $prefix, ?string $suffix = null): array      | Оборачивает значения                                                                                                               |
| join(array $array, string $separator): string                           | Объединяет элементы в строку                                                                                                       |
| insert(array $array, int $index, array $values): array                  | Вставить значения                                                                                                                  |
| firstKey(array $array)                                                  | Возвращает ключ первого элемента                                                                                                   |
| lastKey(array $array)                                                   | Возвращает ключ последнего элемента                                                                                                |
| toggleValue(array $array, $key, $firstValue, $secondValue): array       | Переключает значения                                                                                                               |
| every(array $array, callable $condition): bool                          | Возвращает true, если все элементы удовлетворяют условию                                                                           |
| without(array $array, callable $condition): array                       | Возвращает массив без элементов удовлетворяющих условию                                                                            |
| with(array $array, callable $condition): array                          | Возвращает массив с элементами удовлетворяющими условию                                                                            |
| drop(array $array, int $count): array                                   | Возвращает массив, опуская заданное количество элементов с начала                                                                  |
| dropRight(array $array, int $count): array                              | Возвращает массив, опуская заданное количество элементов с конца                                                                   |
| findValue(array $array, callable $condition)                            | Возвращает первый элемент, который удовлетворяет условию $condition, возвращает false, если такого элемента не существует          |
| findLastValue(array $array, callable $condition)                        | Возвращает последний элемент, который удовлетворяет условию $condition, возвращает false, если такого элемента не существует       |
| findKey(array $array, callable $condition)                              | Возвращает первый ключ элемента, который удовлетворяет условию $condition, возвращает false, если такого элемента не существует    |
| findLastKey(array $array, callable $condition)                          | Возвращает последний ключ элемента, который удовлетворяет условию $condition, возвращает false, если такого элемента не существует |
| mapAndColumn(array $array, $map, $column = null): array                 | Возвращает новый массив с переданным ключем и колонкой                                                                             |

### Коллекции

#### Коллекция значений Fi1a\Collection\Collection

Реализует интерфейс `Fi1a\Collection\CollectionInterface`.

Возможные типы значений:

- mixed
- array
- boolean, bool
- callable
- int, integer
- float, double
- numeric
- string
- resource
- scalar
- object
- название класса

```php
use Fi1a\Collection\Collection;

$collection = new Collection('string');

$collection[] = 'foo';
$collection->add('bar');

foreach ($collection as $item) {
    $item; // 'foo', 'bar'
}

count($collection); // 2

$collection->has(0); // true
$collection->has(1); // true
$collection->has(2); // false
```

#### Коллекции экземпляров классов

Реализует интерфейс `Fi1a\Collection\InstanceCollectionInterface`.

```php
use Fi1a\Collection\AbstractInstanceCollection;

class Foo
{
    private $value = null;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}

class FooCollection extends AbstractInstanceCollection
{
    public static function factory($key, $value)
    {
        return new Foo((string) $value);
    }

    public static function isInstance($value): bool
    {
        return $value instanceof Foo;
    }
}

$collection = new FooCollection(['bar',]);
$collection[] = 'baz';
$collection[] = new Foo('qux');

foreach ($collection as $foo) {
    $foo->getValue(); // 'bar', 'baz', 'qux'
}
```

#### Коллекция экземпляров классов Fi1a\Collection\DataType\ArrayObject

Частная реализация коллекции `Fi1a\Collection\AbstractInstanceCollection` для классов `Fi1a\Collection\DataType\ArrayObject`.

```php
use Fi1a\Collection\ArrayObjectCollection;
use Fi1a\Collection\DataType\ArrayObject;

$collection = new ArrayObjectCollection([['foo',],]);
$collection[] = ['bar',];
$collection[] = new ArrayObject(['baz',]);

foreach ($collection as $item) {
    $item[0]; // 'foo', 'bar', 'baz'
}

count($collection); // 3
```

#### Коллекция экземпляров классов Fi1a\Collection\DataType\MapArrayObject

Частная реализация коллекции `Fi1a\Collection\AbstractInstanceCollection` для классов `Fi1a\Collection\DataType\MapArrayObject`.

```php
use Fi1a\Collection\MapArrayObjectCollection;
use Fi1a\Collection\DataType\MapArrayObject;

$collection = new MapArrayObjectCollection([['foo',],]);
$collection[] = ['bar',];
$collection[] = new MapArrayObject(['baz',]);

foreach ($collection as $item) {
    $item->first(); // 'foo', 'bar', 'baz'
}

$collection->count(); // 3
```

#### Коллекция экземпляров классов Fi1a\Collection\DataType\PathAccess

Частная реализация коллекции `Fi1a\Collection\AbstractInstanceCollection` для классов `Fi1a\Collection\DataType\PathAccess`.

```php
use Fi1a\Collection\PathAccessCollection;
use Fi1a\Collection\DataType\PathAccess;

$collection = new PathAccessCollection([['foo' => ['bar' => 1,],],]);
$collection[] = ['foo' => ['bar' => 2,],];
$collection[] = new PathAccess(['foo' => ['bar' => 3,],]);

foreach ($collection as $item) {
    $item->get('foo:bar'); // 1, 2, 3
}

count($collection); // 3
```

#### Очередь Fi1a\Collection\Queue

Коллекция реализующая двунаправленную очередь упорядоченных элементов.

```php
use Fi1a\Collection\Queue;

$queue = new Queue();
$queue->addBegin(1); // true
$queue->addBegin(2); // true
$queue->pollEnd(); // 1
$queue->pollEnd(); // 2
$queue->pollEnd(); // null
$queue->addBegin(3); // true
$queue->pollEnd(); // 3
$queue->pollEnd(); // null
```

#### Очередь с типизацией значений Fi1a\Collection\TypedValueQueue

Коллекция реализующая двунаправленную очередь упорядоченных элементов с проверкой типов значений.

Возможные типы значений:

- mixed
- array
- boolean, bool
- callable
- int, integer
- float, double
- numeric
- string
- resource
- scalar
- object
- название класса

Пример, в качестве типа boolean:

```php
use Fi1a\Collection\TypedValueQueue;
use Fi1a\Collection\Exception\InvalidArgumentException;

$queue = new TypedValueQueue('boolean');
$queue->addBegin(true); // true
$queue->count(); // 1
$queue->addBegin(false); // true
$queue->count(); // 2
try {
    $queue->addBegin(10); // throw InvalidArgumentException
} catch (InvalidArgumentException $exception) {

}
```

Пример, в качестве типа класс:

```php
use Fi1a\Collection\TypedValueQueue;
use Fi1a\Collection\Exception\InvalidArgumentException;

$queue = new TypedValueQueue(\stdClass::class);
$queue->addBegin(new \stdClass()); // true
try {
    $queue->addBegin(10); // throw InvalidArgumentException
} catch (InvalidArgumentException $exception) {

}
```

[badge-release]: https://img.shields.io/packagist/v/fi1a/collection?label=release
[badge-license]: https://img.shields.io/github/license/fi1a/collection?style=flat-square
[badge-php]: https://img.shields.io/packagist/php-v/fi1a/collection?style=flat-square
[badge-coverage]: https://img.shields.io/badge/coverage-100%25-green
[badge-downloads]: https://img.shields.io/packagist/dt/fi1a/collection.svg?style=flat-square&colorB=mediumvioletred
[badge-mail]: https://img.shields.io/badge/mail-support%40fi1a.ru-brightgreen

[packagist]: https://packagist.org/packages/fi1a/collection
[license]: https://github.com/fi1a/collection/blob/master/LICENSE
[php]: https://php.net
[downloads]: https://packagist.org/packages/fi1a/collection
[mail]: mailto:support@fi1a.ru