<?php
declare(strict_types=1);

namespace Test\Cases\Dummy;

use Test\Cases\StrategyCase;
use Vinograd\Scanner\AbstractTraversalStrategy;
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
     * @param $detect
     * @return void
     */
    public function scanCompleted(AbstractTraversalStrategy $scanStrategy, $detect): void
    {
        $this->testCase->scanCompleted($scanStrategy, $detect);
    }

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param $parentNode
     * @param $currentElement
     * @param $data
     * @return void
     */
    public function visitLeaf(AbstractTraversalStrategy $scanStrategy, $parentNode, $currentElement, $data = null): void
    {
        $this->testCase->visitLeaf($scanStrategy, $parentNode, $currentElement, $data);
    }

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param $parentNode
     * @param $currentNode
     * @param $data
     * @return void
     */
    public function visitNode(AbstractTraversalStrategy $scanStrategy, $parentNode, $currentNode, $data = null): void
    {
        $this->testCase->visitNode($scanStrategy, $parentNode, $currentNode, $data);
    }

    public function equals(Visitor $visitor): bool
    {
        return $this === $visitor;
    }
}