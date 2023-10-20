<?php
declare(strict_types=1);

namespace Test\Unit\BreadthTraversalStrategy;

use Test\Cases\Dummy\DummyNodeFactory;
use Test\Cases\Dummy\TestCaseProviderVisitor;
use Test\Cases\StrategyCase;
use Vinograd\Scanner\AbstractTraversalStrategy;
use Vinograd\Scanner\ArrayDriver;
use Vinograd\Scanner\BreadthStrategy;
use Vinograd\Scanner\NodeFactory;
use Vinograd\Scanner\Verifier;

class BreadthStrategyWithBeforeStopTest extends StrategyCase
{

    public function testDetect()
    {
        $verifier = new Verifier();
        $provider = new TestCaseProviderVisitor($this);
        $strategy = new BreadthStrategy();
        $driver = new ArrayDriver();

        $strategy->setStop(true);
        $strategy->detect([
            'zero',
            5,
            'key1' => 'value',
            'key3' => [
                '4.5' => [
                    4,
                    5,
                    5
                ],
                4
            ],
            'key4' => [
                '4.5' => [
                    4,
                    5,
                    5
                ],
                4
            ],
        ], $driver, new DummyNodeFactory(), $verifier, $verifier, $provider);

        self::assertTrue(true);
    }


    public function scanStarted(AbstractTraversalStrategy $scanStrategy, $detect): void
    {
        self::fail();
    }

    public function scanCompleted(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect): void
    {
        self::fail();
    }

    public function visitLeaf(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {
        self::fail();
    }

    public function visitNode(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {
        self::fail();
    }

}
