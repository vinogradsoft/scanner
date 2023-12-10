<div align="left">
<img alt="Simple Files logo" src="banner.svg">
</div>
<div align="left">

[![codecov](https://codecov.io/gh/vinogradsoft/scanner/graph/badge.svg?token=9KA3S2VXBQ)](https://codecov.io/gh/vinogradsoft/scanner)
<img src="https://badgen.net/static/license/MIT/green">

</div>

# What is Scanner?

> 👉 Scanner is a skeleton for building systems for searching and processing data in hierarchical structures. It offers
> two approaches to data analysis: the first, breadth-first analysis, looks at all levels of the tree in one pass, and
> the second, depth-first analysis, processes each level of the tree in turn, starting from the root. The main purpose
> of this tool is to enable developers to focus on the logic of the application rather than how to traverse trees.
> Scanner can be useful for programmers working with hierarchical data and seeking to automate the process of processing
> such data.

## Features

- 💪 Support for different drivers for different use cases (for
  example, [file driver](https://github.com/vinogradsoft/files-driver) for directory traversal or ArrayDriver for
  working with arrays).
- 👍 Ability to search and process certain elements in tree structures.
- 🚧 Filtering elements at the time of crawling.
- 🤚 Stop tree traversal at any location based on condition.
- ⚗️ Flexibility of use thanks to its own configurations and parameters.

Install
---------

To install with composer:

```
php composer require vinogradsoft/scanner "^2.0"
```

## General Information

The main object in the library is `Vinograd\Scanner\Scanner`. This object accumulates all the traversal settings and
starts traversing the tree.

Bypass algorithms are placed in separate classes, so-called strategies, which can be changed depending on the task.
There are two such strategies implemented in the library: breadth-first (`Vinograd\Scanner\BreadthStrategy`) and
depth-first (`Vinograd\Scanner\SingleStrategy`).

The `Vinograd\Scanner\Visitor` interface is used to process and collect data. There is no implementation for it in the
library; its implementation is carried out by the developer using this library. There are 4 useful methods in this
interface:

- `scanStarted` - called when scanning starts;
- `scanCompleted` - called when the strategy has completed its work;
- `visitLeaf` - called when the strategy visited a leaf of the tree;
- `visitNode` - called when the strategy visited a tree node;

The depth-first traversal algorithm is achieved by the `Vinograd\Scanner\SingleStrategy` strategy. Its algorithm is
quite simple. It receives the children of the node passed to it and exits. The idea is to put a `Scanner` in a `Visitor`
and run the scan repeatedly for each child node in the `visitNode` method. The result is a controlled depth-first
recursive traversal.

In the `Vinograd\Scanner\BreadthStrategy` strategy there is no need to do this; it ends when the last element of the
tree is reached.

Among other things, the `\Vinograd\Scanner\Verifier` object is used during the crawl. Its purpose is to ensure that the
child element meets the requirements and the `visitLeaf` and `visitNode` methods of the `Visitor` object should be
called on the element. In other words, you can provide it with some rules and filter out the elements of the tree. For
the `Vinograd\Scanner\BreadthStrategy` strategy, this does not mean that if a node is filtered, the strategy will not
bypass its child nodes. This means that the `visitLeaf` and `visitNode` methods will not be called on failed elements.
This way, you can configure the bypass to only perform processing on the target nodes. For
the `Vinograd\Scanner\SingleStrategy` strategy this will mean that child nodes will not be scanned, since
the `visitNode` method will not be called and you will not be able to run a scan for it. You can get around this by
relaxing the rules in the `Verifier` object and creating a `Visitor` proxy, which runs a scan for all nodes, but does
not call the `visitNode` method on the proxied object.

The driver allows you to select the type of tree objects that need to be traversed. The library implements a driver for
traversing arrays. The class is called `Vinograd\Scanner\ArrayDriver`. Another external
implementation [files-driver](https://github.com/vinogradsoft/files-driver) allows you to traverse directories in the
file system. Both of these drivers implement the `Vinograd\Scanner\Driver` interface.

## Example

Let's look at a conceptual use case.

> 📢 To make the example clearer, it does not include checks that are typically performed in code. Instead, the example
> focuses on demonstrating the capabilities of the system. The example includes a look at the classes that are needed to
> understand how the system works. You can run the example by cloning
> this [repository](https://github.com/vinogradsoft/example-for-scanner).

### Formulation of the problem

You need to make a console command that triggers a series of commands to be executed in a certain sequence, based on the
configuration. In the configuration, you need to bypass the nodes, starting with `tasks`, and ignore the `other` node.

The configuration looks like this:

```php
<?php
return [
    'tasks' => [
        'make breakfast' => [
            'sandwich' => [
                'cut a piece of bread' => [
                    'take the knife in your right hand',
                    'cut a piece of bread on a wooden board'
                ],
                'spread butter on bread',
                'put a piece of cheese on top',
            ],
            'coffee' => [
                'take a cup',
                'pour coffee into a cup',
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

A node is an array value of type `array`. A leaf is an array value of type `string`.
Each leaf of the tree is a command that we will execute.

### Implementation

> ❗️ To simplify the demo code, we will run `echo`, in other words we will display the names of the nodes and the value of
> the leaves.

Let's write a handler and call it `Handler`. This class implements the `Vinograd\Scanner\Visitor` interface; it is in it
that the names of nodes and the values of tree leaves will be output to the console.

Code:

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
        echo 'Execute: ', $leaf, PHP_EOL;
    }

    public function visitNode(AbstractTraversalStrategy $scanStrategy, mixed $parentNode, mixed $currentNode, mixed $data = null): void
    {
        $nodeName = array_key_first($currentNode);
        echo 'Start: ' . $nodeName, PHP_EOL;
    }
}
```

In order for the code of the `Handler` class to focus only on the logic of displaying names, we will
write `ProxyHandler` to control the traversal of the tree and call `Handler` methods only for the necessary nodes.

Code:

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

In this code, we are most interested in the `visitNode` method, since it contains the logic for depth-first tree
traversal. Since we don't want to print the name of the `tasks` node, we won't call our handler method if the node name
is `tasks`. At the same time, we call the `traverse` method on the Scanner to go further into the tree.

Let's write a filter that will allow us not to scan the `other` node from the configuration.

Code:

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

In this code we check the name of the node and if it is not 'other', we return `true`, otherwise `false`. We are
checking exactly the key in the array. The `$element` argument is an array, containing the entire node and always one
key, which is the name of the node, so using the `array_key_first` function is quite appropriate.

Let's create the main class `Application`, in which we will configure `Scanner` and start traversing the configuration.

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

We complete the Scanner object with the objects necessary for the task and start traversing the tree nodes.

On this line we create a new instance of the Scanner object:

```php
$scanner = new Scanner();
```

Next, install the driver for working with arrays:

```php
$scanner->setDriver(new ArrayDriver());
```

The next step is to install `ProxyHandler`, passing our handler and `Scanner` to it in the constructor. We complete this
stage by setting up a strategy for depth-first traversal.

Code:

```php
$scanner->setVisitor(new ProxyHandler(new Handler(), $scanner));
$scanner->setStrategy(new SingleStrategy());
```

In these lines:

```php
$scanner->addNodeFilter(new FilterForNodeOther());
$scanner->traverse($config);
```

We add a filter that allows us not to scan the `other` node and start crawling the nodes.

## Where is it used?

Scanner is used in:

- [File-search](https://github.com/vinogradsoft/file-search) - a library that allows you to search for the necessary
  files and do something with them;
- [Reflection](https://github.com/vinogradsoft/reflection) - a library that creates an object model of the specified
  directory and allows you to manipulate it: copy, modify files, delete, move and create new ones.

---

⭐️ **Please leave a star if you find the project useful!**

## Testing

``` php composer tests ```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see License [File](LICENSE) for more information.
