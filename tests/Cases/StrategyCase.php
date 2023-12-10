<?php
declare(strict_types=1);

namespace Test\Cases;

use PHPUnit\Framework\TestCase;
use Vinograd\Scanner\AbstractTraversalStrategy;

abstract class StrategyCase extends TestCase
{
    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param $detect
     */
    abstract public function scanStarted(AbstractTraversalStrategy $scanStrategy, $detect): void;

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param $detect
     * @return void
     */
    abstract public function scanCompleted(AbstractTraversalStrategy $scanStrategy, $detect): void;

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param $detect
     * @param $found
     * @param $data
     * @return void
     */
    abstract public function visitLeaf(AbstractTraversalStrategy $scanStrategy,  $detect, $found, $data = null): void;

    /**
     * @param AbstractTraversalStrategy $scanStrategy
     * @param $detect
     * @param $found
     * @param $data
     * @return void
     */
    abstract public function visitNode(AbstractTraversalStrategy $scanStrategy,  $detect, $found, $data = null): void;
}