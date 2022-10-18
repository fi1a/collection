# Структуры данных и коллекции в PHP

[![Latest Version][badge-release]][packagist]
[![Software License][badge-license]][license]
[![PHP Version][badge-php]][php]
![Coverage Status][badge-coverage]
[![Total Downloads][badge-downloads]][downloads]

Пакет fi1a/collection предоставляет классы структур данных и коллекций.

## Установка

Установить этот пакет можно как зависимость, используя Composer.

``` bash
composer require fi1a/collection ~1.2
```

## Использование

### Типы данных

#### Объект Fi1a\Collection\DataType\ArrayObject, работающий также как и массив

Реализует интерфейс Fi1a\Collection\DataType\IArrayObject.

```php
use Fi1a\Collection\DataType\ArrayObject;

$arrayObject = new ArrayObject(['foo', 'bar', 'baz',]);

foreach ($arrayObject as $value) {
    $value; // 'foo', 'bar', 'baz'
}

count($arrayObject); // 3
```

#### Объект Fi1a\Collection\DataType\MapArrayObject, работающий также как и массив

Реализует интерфейс Fi1a\Collection\DataType\IMapArrayObject с методами расширяющими использование.

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

#### Fi1a\Collection\DataType\PathAccess класс реализует доступ по пути к значениям

Реализует интерфейс Fi1a\Collection\DataType\IPathAccess. Данный класс позволяет получать доступ к ключам массива по пути (foo:bar:baz).

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

Реализует интерфейс Fi1a\Collection\DataType\IValueObject. Предоставляет возможность задать set/get методы для значений и работает также как и массив.

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

### Коллекции

#### Коллекция значений Fi1a\Collection\Collection

Реализует интерфейс Fi1a\Collection\ICollection.

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

Реализует интерфейс Fi1a\Collection\IInstanceCollection.

```php
use Fi1a\Collection\AInstanceCollection;

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

class FooCollection extends AInstanceCollection
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

Частная реализация коллекции Fi1a\Collection\AInstanceCollection для классов ArrayObject.

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

Частная реализация коллекции Fi1a\Collection\AInstanceCollection для классов MapArrayObject.

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

Частная реализация коллекции Fi1a\Collection\AInstanceCollection для классов PathAccess.

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

[packagist]: https://packagist.org/packages/fi1a/collection
[license]: https://github.com/fi1a/collection/blob/master/LICENSE
[php]: https://php.net
[downloads]: https://packagist.org/packages/fi1a/collection