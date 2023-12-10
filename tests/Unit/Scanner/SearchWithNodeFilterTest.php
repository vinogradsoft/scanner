<?php
declare(strict_types=1);

namespace Test\Unit\Scanner;

use Test\Cases\Dummy\DummyNodeFactory;
use Test\Cases\Dummy\TestCaseProviderVisitor;
use Test\Cases\StrategyCase;
use Vinograd\Scanner\AbstractTraversalStrategy;
use Vinograd\Scanner\ArrayDriver;
use Vinograd\Scanner\BreadthStrategy;
use Vinograd\Scanner\Filter;
use Vinograd\Scanner\Scanner;

class SearchWithNodeFilterTest extends StrategyCase
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
        $driver = new ArrayDriver();

        $scanner = new Scanner();
        $scanner->setStrategy($strategy);
        $scanner->setVisitor($provider);
        $scanner->setDriver($driver);

        $scanner->addNodeFilter(new class() implements Filter {

            public function filter($element): bool
            {
                $key = array_keys($element)[0];
                if ($key === 'key3') {
                    return false;
                }
                return true;
            }

            public function setConfiguration($config): void
            {

            }
        });

        $scanner->traverse($array);

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

    public function scanCompleted(AbstractTraversalStrategy $scanStrategy, $detect): void
    {

    }

    public function visitLeaf(AbstractTraversalStrategy $scanStrategy, $detect, $found, $data = null): void
    {
        $this->leafCounter++;
        $this->leafLog [] = $found;
    }

    public function visitNode(AbstractTraversalStrategy $scanStrategy, $detect, $found, $data = null): void
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