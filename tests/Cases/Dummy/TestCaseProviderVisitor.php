<?php

namespace Test\Cases\Dummy;

use Test\Cases\StrategyCase;
use Vinograd\Scanner\AbstractTraversalStrategy;
use Vinograd\Scanner\NodeFactory;
use Vinograd\Scanner\Visitor;

class TestCaseProviderVisitor implements Visitor
{
    /** @var StrategyCase */
    protected $testCase;

    /**
     * @param StrategyCase $testCase
     */
    public function __construct(StrategyCase $testCase)
    {
        $this->testCase = $testCase;
    }

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param $detect
     */
    public function scanStarted(AbstractTraversalStrategy $scanStrategy, $detect): void
    {
        $this->testCase->scanStarted($scanStrategy, $detect);
    }

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param NodeFactory $factory
     * @param $detect
     */
    public function scanCompleted(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect): void
    {
        $this->testCase->scanCompleted($scanStrategy, $factory, $detect);
    }

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param NodeFactory $factory
     * @param $detect
     * @param $found
     * @param null $data
     */
    public function visitLeaf(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {
        $this->testCase->visitLeaf($scanStrategy, $factory, $detect, $found, $data);
    }

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param NodeFactory $factory
     * @param $detect
     * @param $found
     * @param null $data
     */
    public function visitNode(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {
        $this->testCase->visitNode($scanStrategy, $factory, $detect, $found, $data);
    }

    public function equals(Visitor $visitor): bool
    {
        return $this === $visitor;
    }
}