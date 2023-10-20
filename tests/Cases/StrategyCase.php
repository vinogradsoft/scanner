<?php
declare(strict_types=1);

namespace Test\Cases;

use PHPUnit\Framework\TestCase;
use Vinograd\Scanner\AbstractTraversalStrategy;
use Vinograd\Scanner\NodeFactory;

abstract class StrategyCase extends TestCase
{
    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param $detect
     */
    abstract public function scanStarted(AbstractTraversalStrategy $scanStrategy, $detect): void;

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param NodeFactory $factory
     * @param $detect
     */
    abstract public function scanCompleted(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect): void;

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param NodeFactory $factory
     * @param $detect
     * @param $found
     * @param null $data
     */
    abstract public function visitLeaf(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void;

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param NodeFactory $factory
     * @param $detect
     * @param $found
     * @param null $data
     */
    abstract public function visitNode(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void;
}