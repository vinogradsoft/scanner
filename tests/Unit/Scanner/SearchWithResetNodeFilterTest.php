<?php

namespace Test\Unit\Scanner;

use Test\Cases\Dummy\DummyNodeFactory;
use Test\Cases\Dummy\TestCaseProviderVisitor;
use Test\Cases\StrategyCase;
use Vinograd\Scanner\AbstractTraversalStrategy;
use Vinograd\Scanner\ArrayDriver;
use Vinograd\Scanner\BreadthStrategy;
use Vinograd\Scanner\Filter;
use Vinograd\Scanner\NodeFactory;
use Vinograd\Scanner\Scanner;

class SearchWithResetNodeFilterTest extends StrategyCase
{

    private $leafCounter = 0;
    private $nodeCounter = 0;
    private $nodeLog = [];
    private $leafLog = [];

    /**
     * @dataProvider getCase
     */
    public function testSearch($array, $expectedLeaf, $expectedNodes)
    {
        $provider = new TestCaseProviderVisitor($this);
        $strategy = new BreadthStrategy();
        $driver = new ArrayDriver(new DummyNodeFactory());

        $scanner = new Scanner();
        $scanner->setStrategy($strategy);
        $scanner->setVisitor($provider);
        $scanner->setDriver($driver);

        $scanner->addNodeFilter(new class() implements Filter {

            public function filter($found): bool
            {
                $key = array_keys($found)[0];
                if ($key === 'key3') {
                    return false;
                }
                return true;
            }

            public function setConfiguration($config): void
            {

            }
        });
        $scanner->resetNodeFilters();
        $scanner->search($array);

        self::assertCount($this->leafCounter, $expectedLeaf);
        self::assertCount($this->nodeCounter, $expectedNodes);

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
                        '4.5' => [
                            4,
                            5,
                            5
                        ],
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
        $this->leafCounter = 0;
        $this->nodeCounter = 0;
        $this->nodeLog = [];
        $this->leafLog = [];
    }
}