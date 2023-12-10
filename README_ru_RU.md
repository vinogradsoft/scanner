<div align="left">
<img alt="Simple Files logo" src="banner.svg">
</div>
<div align="left">

[![codecov](https://codecov.io/gh/vinogradsoft/scanner/graph/badge.svg?token=9KA3S2VXBQ)](https://codecov.io/gh/vinogradsoft/scanner)
<img src="https://badgen.net/static/license/MIT/green">

</div>

# Что такое Scanner?

> 👉 Scanner - это каркас для построения систем поиска и обработки данных в иерархических структурах. Он предлагает
> два подхода к анализу данных: первый, анализ “в ширину”, просматривает все уровни дерева за один проход, а второй,
> анализ “вглубь”, обрабатывает каждый уровень дерева по очереди, начиная с корня. Основная цель этого инструмента -
> предоставить возможность разработчикам концентрироваться на логике приложения, а не том, как обходить деревья. Scanner
> может быть полезен для программистов, работающих с иерархическими данными и стремящихся автоматизировать процесс
> обработки таких данных.

## Особенности библиотеки:

- 💪 Поддержка различных драйверов для разных сценариев использования (например,
  [файловый драйвер](https://github.com/vinogradsoft/files-driver) для обхода директорий или ArrayDriver для работы с
  массивами).
- 👍 Возможность поиска и обработки определённых элементов в древовидных структурах.
- 🚧 Фильтрация элементов в моменте обхода.
- 🤚 Остановка обхода дерева в любом месте по условию.
- ⚗️ Гибкость использования благодаря своим собственным конфигурациям и параметрам.

Установка
---------

Предпочтительный способ установки - через [composer](http://getcomposer.org/download/).

Запустите команду

```
php composer require vinogradsoft/scanner "^2.0"
```

## Общая информация

Основным объектом в библиотеке является `Vinograd\Scanner\Scanner`. Этот объект аккумулирует в себе все настройки обхода
и запускает обход дерева.

Алгоритмы обхода вынесены в отдельные классы, так называемые стратегии, которые можно менять в зависимости от задачи.
Таких стратегий в библиотеке реализовано две: в ширину (`Vinograd\Scanner\BreadthStrategy`) и в
глубину (`Vinograd\Scanner\SingleStrategy`).

Для обработки и сбора данных используется интерфейс `Vinograd\Scanner\Visitor`. В библиотеке нет для него реализации,
его реализацией занимается разработчик, использующий эту библиотеку. В этом интерфейсе 4 полезных метода:

- `scanStarted` - вызывается, когда стартует обход;
- `scanCompleted` - вызывается, когда стратегия завершила свою работу;
- `visitLeaf` - вызывается, когда стратегия посетила лист дерева;
- `visitNode` - вызывается, когда стратегия посетила узел дерева;

Алгоритм обхода в глубину достигается стратегией `Vinograd\Scanner\SingleStrategy`. Ее алгоритм довольно прост. Она
получает дочерние элементы переданного ей узла и завершается. Идея в том, чтобы поместить `Scanner` в `Visitor`
и запускать сканирование повторно для каждого дочернего узла в методе `visitNode`. В итоге получится контролируемый
рекурсивный обход в глубину.

В стратегии `Vinograd\Scanner\BreadthStrategy` так делать не нужно, она завершается, когда будет достигнут последний
элемент дерева.

Кроме прочего, в моменте обхода используется объект `\Vinograd\Scanner\Verifier`. Его целью является достижение гарантии
того, что дочерний элемент соответствует требованиям, и для элемента следует вызывать методы `visitLeaf` и `visitNode`
объекта `Visitor`. Другими словами, его можно снабдить некоторыми правилами и отфильтровать элементы дерева. Для
стратегии `Vinograd\Scanner\BreadthStrategy` это не означает, что если узел отфильтрован, обходить его дочерние узлы
стратегия не будет. Это означает, что методы `visitLeaf` и `visitNode` не будут вызваны для не прошедших проверку
элементов. Таким образом, можно настроить обход так, чтобы обработка выполнялась только на целевых узлах. Для
стратегии `Vinograd\Scanner\SingleStrategy` это будет означать, что дочерние узлы просканированы не будут, так как
метод `visitNode` не будет вызван, и вы не сможете запустить сканирование для него. Обойти это можно, смягчив правила в
объекте `Verifier` и создав прокси `Visitor`, в котором запускать сканирование для всех узлов, но не вызывать
метод `visitNode` у проксируемого объекта.

Драйвер позволяет выбирать тип объектов дерева, которые нужно обходить. В библиотеке реализован драйвер для обхода
массивов. Класс называется `Vinograd\Scanner\ArrayDriver`. Еще одна внешняя
реализация [files-driver](https://github.com/vinogradsoft/files-driver) позволяет делать обход директорий в файловой
системе. Оба этих драйвера реализуют интерфейс `Vinograd\Scanner\Driver`.

## Пример

Рассмотрим концептуальный пример использования.

> 📢 Чтобы сделать пример более понятными, в нем не включены проверки, которые обычно выполняются в коде. Вместо этого
> пример фокусируется на демонстрации возможностей системы. Пример включает в себя рассмотрение классов, которые нужны
> для понимания работы системы. Запустить пример можно, клонировав себе
> этот [репозиторий](https://github.com/vinogradsoft/example-for-scanner).

### Постановка задачи

Требуется сделать консольную команду, которая запускает выполнение в определенной последовательности ряд команд,
основываясь на конфигурации. В конфигурации нужно обойти узлы, начиная с `tasks`, а узел `other` игнорировать.

Конфигурация выглядит так:

```php
<?php
return [
    'tasks' => [
        'сделать завтрак' => [
            'бутерброд' => [
                'отрезать кусок хлеба' => [
                    'взять нож в правую руку',
                    'отрезать на деревянной доске кусок хлеба'
                ],
                'намазать хлеб маслом',
                'положить сверху кусок сыра',
            ],
            'кофе' => [
                'взять чашку',
                'налить кофе в чашку',
            ],
        ]
    ],
    'other' => [
        'setting1' => 'value1',
        'setting2' => 'value2',
        'setting3' => 'value3'
    ]
];
```

Узлом является значение массива с типом `array`. Листом считается значение массива с типом `string`.
Каждый лист дерева - это команда, которую будем выполнять.

### Реализация

> ❗️ Для простоты демонстрационного кода запускать мы будем `echo`, другими словами будем выводить названия узлов и
> значение листьев.

Напишем обработчик и назовем его `Handler`. Этот класс реализует интерфейс `Vinograd\Scanner\Visitor`, именно в нем
будут выводиться в консоль название узлов и значения листьев дерева.

Код:

```php
<?php
declare(strict_types=1);

namespace Example;

use Vinograd\Scanner\AbstractTraversalStrategy;
use Vinograd\Scanner\Visitor;

class Handler implements Visitor
{
    public function scanStarted(AbstractTraversalStrategy $scanStrategy, mixed $detect): void
    {
    }

    public function scanCompleted(AbstractTraversalStrategy $scanStrategy, mixed $detect): void
    {
    }

    public function visitLeaf(AbstractTraversalStrategy $scanStrategy, mixed $parentNode, mixed $currentElement, mixed $data = null): void
    {
        $leaf = array_shift($currentElement);
        echo 'Выполнение: ', $leaf, PHP_EOL;
    }

    public function visitNode(AbstractTraversalStrategy $scanStrategy, mixed $parentNode, mixed $currentNode, mixed $data = null): void
    {
        $nodeName = array_key_first($currentNode);
        echo 'Старт: ' . $nodeName, PHP_EOL;
    }
}
```

Для того чтобы код класса `Handler` был сосредоточен только на логике вывода названий, напишем `ProxyHandler`, чтобы
управлять обходом дерева и вызывать методы `Handler` только для нужных узлов.

Код:

```php
<?php
declare(strict_types=1);

namespace Example;

use Vinograd\Scanner\AbstractTraversalStrategy;
use Vinograd\Scanner\Scanner;
use Vinograd\Scanner\Visitor;

class ProxyHandler implements Visitor
{
    private Visitor $handler;
    private Scanner $scanner;

    public function __construct(Visitor $handler, Scanner $scanner)
    {
        $this->handler = $handler;
        $this->scanner = $scanner;
    }

    public function scanStarted(AbstractTraversalStrategy $scanStrategy, mixed $detect): void
    {
        $this->handler->scanStarted($scanStrategy, $detect);
    }

    public function scanCompleted(AbstractTraversalStrategy $scanStrategy, mixed $detect): void
    {
        $this->handler->scanCompleted($scanStrategy, $detect);
    }

    public function visitLeaf(AbstractTraversalStrategy $scanStrategy, mixed $parentNode, mixed $currentElement, mixed $data = null): void
    {
        $this->handler->visitLeaf($scanStrategy, $parentNode, $currentElement);
    }

    public function visitNode(AbstractTraversalStrategy $scanStrategy, mixed $parentNode, mixed $currentNode, mixed $data = null): void
    {
        $name = array_key_first($currentNode);
        if ($name !== 'tasks') {
            $this->handler->visitNode($scanStrategy, $parentNode, $currentNode);
        }
        $this->scanner->traverse($currentNode[$name]);
    }
}
```

В этом коде больше всего нас интересует метод `visitNode`, поскольку в нем и есть логика обхода дерева в глубину.
Так как нам не хочется выводить название ноды `tasks`, мы не будем вызывать метод нашего обработчика, если название ноды
равно `tasks`. При этом мы вызываем метод `traverse` у Scanner-а, чтобы пройти дальше вглубь дерева.

Напишем фильтр, который нам позволит не сканировать узел `other` из конфигурации.

Код:

```php
<?php
declare(strict_types=1);

namespace Example;

class FilterForNodeOther implements \Vinograd\Scanner\Filter
{
    public function filter(mixed $element): bool
    {
        $name = array_key_first($element);
        return $name !== 'other';
    }
}
```

В этом коде мы проверяем название ноды и если оно не 'other', возвращаем `true`, иначе `false`.
Мы проверяем именно ключ в массиве. Аргумент `$element` является массивом, содержит весь узел и всегда один ключ,
который является названием ноды, поэтому использование функции `array_key_first` вполне уместно.

Создадим главный класс `Application`, в котором настроим `Scanner` и запустим обход конфигурации.

```php
<?php
declare(strict_types=1);

namespace Example;

use Vinograd\Scanner\ArrayDriver;
use Vinograd\Scanner\Scanner;
use Vinograd\Scanner\SingleStrategy;

class Application
{
    public function run(array $config): void
    {
        $scanner = new Scanner();
        $scanner->setDriver(new ArrayDriver());
        $scanner->setVisitor(new ProxyHandler(new Handler(), $scanner));
        $scanner->setStrategy(new SingleStrategy());
        $scanner->addNodeFilter(new FilterForNodeOther());
        $scanner->traverse($config);
    }
}
```

Комплектуем объект Scanner нужными для задачи объектами и запускаем обход узлов дерева.

В этой строке мы создаем новый экземпляр объекта Scanner:

```php
$scanner = new Scanner();
```

Далее устанавливаем драйвер для работы с массивами:

```php
$scanner->setDriver(new ArrayDriver());
```

Следующим этапом устанавливаем `ProxyHandler`, передав ему в конструктор наш обработчик и `Scanner`. Завершаем этот
этап установкой стратегии для обхода в глубину.

Код:

```php
$scanner->setVisitor(new ProxyHandler(new Handler(), $scanner));
$scanner->setStrategy(new SingleStrategy());
```

В этих строках:

```php
$scanner->addNodeFilter(new FilterForNodeOther());
$scanner->traverse($config);
```

Добавляем фильтр, который позволяет не сканировать узел `other` и запускаем обход узлов.

## Где используется?

Scanner используется в:

- [File-search](https://github.com/vinogradsoft/file-search) - библиотека, которая позволяет искать нужные файлы и
  что-то с ними делать;
- [Reflection](https://github.com/vinogradsoft/reflection) - библиотека, которая создает объектную модель указанной
  директории и позволяет манипулировать ею: копировать, модифицировать файлы, удалять, перемещать и создавать новые.

---

⭐️ **Поставьте звездочку, если находите проект полезным!**

## Тестировать

```
 php composer tests 
```

## Содействие

Пожалуйста, смотрите [ВКЛАД](CONTRIBUTING.md) для получения подробной информации.

## Лицензия

Лицензия MIT (MIT). Пожалуйста, смотрите [файл лицензии](LICENSE) для получения дополнительной информации.