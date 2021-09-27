<?php

namespace Test\Unit\BreadthTraversalStrategy;

use Test\Cases\Dummy\DummyNodeFactory;
use Test\Cases\Dummy\TestCaseProviderVisitor;
use Test\Cases\StrategyCase;
use Vinograd\Scanner\AbstractTraversalStrategy;
use Vinograd\Scanner\ArrayDriver;
use Vinograd\Scanner\BreadthStrategy;
use Vinograd\Scanner\NodeFactory;
use Vinograd\Scanner\Verifier;

class BreadthStrategyWithStopTest extends StrategyCase
{
    private $strategy;
    private $driver;
    private $factory;
    private $detect;
    private $provider;

    public function setUp(): void
    {
        $this->provider = new TestCaseProviderVisitor($this);
        $this->strategy = new BreadthStrategy();
        $this->factory = new DummyNodeFactory();
        $this->driver = new ArrayDriver($this->factory);
    }

    /**
     * @dataProvider getCase
     */
    public function testDetect($array)
    {
        $verifier = new Verifier();
        $this->strategy->detect($this->detect = $array, $this->driver, $verifier, $verifier, $this->provider);
    }

    public function getCase()
    {
        return [
            [//line1
                [
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
                ],
            ],
        ];
    }

    public function scanStarted(AbstractTraversalStrategy $scanStrategy, $detect): void
    {
        self::assertTrue(true);
    }

    public function scanCompleted(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect): void
    {
        self::assertTrue(true);
    }

    public function visitLeaf(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {
        self::assertCount(1, $found);
        $key = array_keys($found)[0];
        $value = $found[$key];
        if (5 == $value) {
            self::assertTrue(true);
            $scanStrategy->setStop(true);
        }
    }

    public function visitNode(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {
        self::assertTrue(true);
    }

    public function tearDown(): void
    {
        $this->strategy = null;
        $this->driver = null;
        $this->factory = null;
        $this->detect = null;
    }
}
