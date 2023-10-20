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

class BreadthStrategyTest extends StrategyCase
{
    private $strategy;
    private $driver;
    private $factory;
    private $detect;

    private $leafCounter = 0;
    private $nodeCounter = 0;
    private $nodeLog = [];
    private $leafLog = [];
    private $provider;

    public function setUp(): void
    {
        $this->provider = new TestCaseProviderVisitor($this);
        $this->strategy = new BreadthStrategy();;
        $this->factory = new DummyNodeFactory();
        $this->driver = new ArrayDriver();
    }

    /**
     * @dataProvider getCase
     */
    public function testDetect($array, $expectedLeaf, $expectedNodes)
    {
        $verifier = new Verifier();
        $this->strategy->detect($this->detect = $array, $this->driver, $this->factory, $verifier, $verifier, $this->provider);
        self::assertEquals($this->leafCounter, count($expectedLeaf));
        self::assertEquals($this->nodeCounter, count($expectedNodes));

        self::assertEquals($expectedLeaf, $this->leafLog);
        self::assertEquals($expectedNodes, $this->nodeLog);
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
                [//leaf
                    [0 => 'zero'],
                    [1 => 5],
                    ['key1' => 'value'],
                    [0 => 4],
                    [0 => 4],
                    [0 => 4],
                    [1 => 5],
                    [2 => 5],
                    [0 => 4],
                    [1 => 5],
                    [2 => 5],
                ],
                [//nodes
                    ['key3' => [
                        '4.5' => [4, 5, 5],
                        4
                    ]],
                    ['key4' => [
                        '4.5' => [4, 5, 5],
                        4
                    ]],
                    ['4.5' => [4, 5, 5]],
                    ['4.5' => [4, 5, 5]],
                ]
            ],
        ];
    }


    public function scanStarted(AbstractTraversalStrategy $scanStrategy, $detect): void
    {

    }

    public function scanCompleted(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect): void
    {

    }

    public function visitLeaf(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {
        $this->leafCounter++;
        $this->leafLog [] = $found;
    }

    public function visitNode(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {

        $this->nodeCounter++;
        $this->nodeLog[] = $found;
    }

    public function tearDown(): void
    {
        $this->strategy = null;
        $this->driver = null;
        $this->factory = null;
        $this->detect = null;

        $this->leafCounter = 0;
        $this->nodeCounter = 0;
        $this->nodeLog = [];
        $this->leafLog = [];
    }
}
