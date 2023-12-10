<?php
declare(strict_types=1);

namespace Test\Unit\SingleTraversalStrategy;

use Test\Cases\Dummy\DummyNodeFactory;
use Test\Cases\Dummy\TestCaseProviderVisitor;
use Test\Cases\StrategyCase;
use Vinograd\Scanner\AbstractTraversalStrategy;
use Vinograd\Scanner\ArrayDriver;
use Vinograd\Scanner\SingleStrategy;
use Vinograd\Scanner\Verifier;

class SingleStrategyDetectWithBeforeStopTest extends StrategyCase
{

    public function testDetect()
    {
        $provider = new TestCaseProviderVisitor($this);
        $strategy = new SingleStrategy();
        $driver = new ArrayDriver();
        $verifier = new Verifier();

        $strategy->setStop(true);

        $strategy->detect([
            'zero',
            'key1' => 'value',
            'key2' => 'value2',
            'key3' => [4.5, 4],
            'key4' => [1, 3, 2]
        ], $driver, $verifier, $verifier, $provider);

        self::assertTrue(true);
    }

    public function scanStarted(AbstractTraversalStrategy $scanStrategy, $detect): void
    {
        self::fail();
    }

    public function scanCompleted(AbstractTraversalStrategy $scanStrategy, $detect): void
    {
        self::fail();
    }

    public function visitLeaf(AbstractTraversalStrategy $scanStrategy, $detect, $found, $data = null): void
    {
        self::fail();
    }

    public function visitNode(AbstractTraversalStrategy $scanStrategy, $detect, $found, $data = null): void
    {
        self::fail();
    }

}
