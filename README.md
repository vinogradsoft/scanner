<div align="left">
<img alt="Simple Files logo" src="banner.svg">
</div>
<div align="left">

[![codecov](https://codecov.io/gh/vinogradsoft/scanner/graph/badge.svg?token=9KA3S2VXBQ)](https://codecov.io/gh/vinogradsoft/scanner)
<img src="https://badgen.net/static/license/MIT/green">

</div>

# What is Scanner?

> 👉 Scanner is a framework for building systems for searching and processing data in hierarchical structures. It offers
> two approaches to data analysis: the first, breadth-first analysis, looks at all levels of the tree in one pass, and
> the
> second, depth-first analysis, processes each level of the tree in turn, starting from the root. The main purpose of
> this
> tool is to automate the process of searching and processing the necessary tree elements. Scanner can be useful for
> programmers working with hierarchical data and seeking to automate the process of processing such data.

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
library; its implementation is carried out by the developer using this library. There are 5 useful methods in this
interface:

- `scanStarted` - calls when the scan starts;
- `scanCompleted` - called when the strategy has completed its work;
- `visitLeaf` - called when the strategy visited a leaf of the tree;
- `visitNode` - called when the strategy visited a tree node;
- `equals` - auxiliary method, compares itself with the `Visitor` object passed to its argument.

The depth-first traversal algorithm is achieved by the `Vinograd\Scanner\SingleStrategy` strategy. Its algorithm is
quite simple. It receives the children of the node passed to it and exits. The idea is to put a `Scanner` in a `Visitor`
and run the scan repeatedly for each child node in the `visitNode` method. The result is a controlled, recursive
depth-first traversal.

In the strategy `Vinograd\Scanner\BreadthStrategy` there is no need to do this; it ends when the last element of the
tree is reached.

Among other things, the `\Vinograd\Scanner\Verifier` object is used during the crawl. Its purpose is to ensure that the
child element meets the requirements and the `visitLeaf` and `visitNode` methods of the `Visitor` object should be
called on the element. In other words, you can provide it with some rules and filter out the elements of the tree. For
the `Vinograd\Scanner\BreadthStrategy` strategy, this does not mean that if a node is filtered, the strategy will not
bypass its child nodes. This means that the `visitLeaf` and `visitNode` methods will not be called on elements that fail
validation. This way, you can configure the bypass to only perform processing on the target nodes. For
the `Vinograd\Scanner\SingleStrategy` strategy this will mean that child nodes will not be scanned, since
the `visitNode` method will not be called and you will not be able to run a scan for it. You can get around this by
relaxing the rules in the `Verifier` object and creating a `Visitor` proxy in which to run scanning for all nodes, but
not calling the `visitNode` method on the proxied object.

The driver allows you to select the type of tree objects that need to be traversed. The library implements a driver for
traversing arrays. The class is called `Vinograd\Scanner\ArrayDriver`. Another external
implementation [files-driver](https://github.com/vinogradsoft/files-driver) allows you to traverse directories in the
file system. Both of these drivers implement the `Vinograd\Scanner\Driver` interface.

## Where is it used?

Scanner is used in:

- [File-search](https://github.com/vinogradsoft/file-search) - a library that allows you to search for the necessary
  files and do something with them;
- [Reflection](https://github.com/vinogradsoft/reflection) - a library that creates an object model of the specified
  directory and allows you to manipulate it: copy, modify files, delete, move and create new ones.

## Testing

``` php composer tests ```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see License [File](LICENSE) for more information.
