<?php
declare(strict_types=1);

namespace Test\Unit\Scanner;

use Test\Cases\Dummy\TestCaseProviderVisitor;
use Test\Cases\StrategyCase;
use Vinograd\Scanner\AbstractTraversalStrategy;
use Vinograd\Scanner\ArrayDriver;
use Vinograd\Scanner\BreadthStrategy;
use Vinograd\Scanner\Filter;
use Vinograd\Scanner\Scanner;

class SearchWithLeafFilterTest extends StrategyCase
{
    private $strategy;
    private $driver;

    private $leafCounter = 0;
    private $nodeCounter = 0;
    private $nodeLog = [];
    private $leafLog = [];
    private $provider;

    public function setUp(): void
    {
        $this->provider = new TestCaseProviderVisitor($this);
        $this->strategy = new BreadthStrategy();;
        $this->driver = new ArrayDriver();
    }

    /**
     * @dataProvider getCase
     */
    public function testSearch($array, $expectedLeaf, $expectedNodes)
    {
        $scanner = new Scanner();
        $scanner->setStrategy($this->strategy);
        $scanner->setVisitor($this->provider);
        $scanner->setDriver($this->driver);

        $scanner->addLeafFilter(new class() implements Filter {

            public function filter($element): bool
            {
                $value = array_values($element)[0];
                if ('zero' === $value) {
                    return false;
                }
                if ('value' === $value) {
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
                    [1 => 5],
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
        $this->strategy = null;
        $this->driver = null;
        $this->factory = null;

        $this->leafCounter = 0;
        $this->nodeCounter = 0;
        $this->nodeLog = [];
        $this->leafLog = [];
    }
}