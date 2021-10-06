<?php

namespace Test\Unit\SingleTraversalStrategy;

use Test\Cases\Dummy\DummyNodeFactory;
use Test\Cases\Dummy\TestCaseProviderVisitor;
use Test\Cases\StrategyCase;
use Vinograd\Scanner\AbstractTraversalStrategy;
use Vinograd\Scanner\ArrayDriver;
use Vinograd\Scanner\NodeFactory;
use Vinograd\Scanner\SingleStrategy;
use Vinograd\Scanner\Verifier;

class SingleStrategyDetectTest extends StrategyCase
{
    private $strategy;
    private $driver;
    private $factory;
    private $detect;
    private $array;
    private $except;
    private $provider;

    public function setUp(): void
    {
        $this->provider = new TestCaseProviderVisitor($this);
        $this->strategy = new SingleStrategy();
        $this->factory = new DummyNodeFactory();
        $this->driver = new ArrayDriver();
    }

    /**
     * @dataProvider getCase
     */
    public function testDetect($array, $except)
    {
        $this->array = $array;
        $this->except = $except;
        $verifier = new Verifier();
        $this->strategy->detect($this->detect = $array, $this->driver, $this->factory, $verifier, $verifier, $this->provider);
    }

    public function getCase()
    {
        return [
            [//line1
                [
                    'zero',
                    'key1' => 'value',
                    'key2' => 'value2',
                    'key3' => [4.5, 4],
                    'key4' => [1, 3, 2]
                ],
                [
                    'leaf' => [
                        [0 => 'zero'],
                        ['key1' => 'value'],
                        ['key2' => 'value2']
                    ],
                    'node' => [
                        ['key3' => [4.5, 4]],
                        ['key4' => [1, 3, 2]]
                    ]
                ]
            ],
            [//line2
                [
                    'key3' => [4.5, 4],
                    'key4' => [1, 3, 2]
                ],
                [
                    'leaf' => [
                    ],
                    'node' => [
                        ['key3' => [4.5, 4]],
                        ['key4' => [1, 3, 2]]
                    ]
                ]
            ],
            [//line3
                [
                    'zero',
                    'key1' => 'value',
                    'key2' => 'value2',
                ],
                [
                    'leaf' => [
                        [0 => 'zero'],
                        ['key1' => 'value'],
                        ['key2' => 'value2']
                    ],
                    'node' => [
                    ]
                ]
            ],
            [//line4
                [
                    'zero',
                    'zero',
                    0,
                    'key0' => 'value',
                    'key1' => 'value',
                    'key2' => 'value2',
                    'key3' => [4.5, 4],
                    'key4' => [1, 3, 2],
                    'key5' => [1, 3, 2]
                ],
                [
                    'leaf' => [
                        [0 => 'zero'],
                        [1 => 'zero'],
                        [2 => 0],
                        ['key0' => 'value'],
                        ['key1' => 'value'],
                        ['key2' => 'value2']
                    ],
                    'node' => [
                        ['key3' => [4.5, 4]],
                        ['key4' => [1, 3, 2]],
                        ['key5' => [1, 3, 2]]
                    ]
                ]
            ],
        ];
    }

    public function scanStarted(AbstractTraversalStrategy $scanStrategy, $detect): void
    {
        self::assertEquals($this->array, $detect);
        self::assertEquals($this->strategy, $scanStrategy);
    }

    public function scanCompleted(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect): void
    {
        self::assertEmpty($this->except['node']);
        self::assertEmpty($this->except['leaf']);
    }

    public function visitLeaf(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {
        self::assertCount(1, $found);
        $key = array_keys($found)[0];
        $value = $found[$key];
        foreach ($this->except['leaf'] as $idx => $item) {
            if (array_key_exists($key, $item) && $value === $item[$key]) {
                unset($this->except['leaf'][$idx]);
                return;
            }
        }
    }

    public function visitNode(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {
        self::assertCount(1, $found);
        $key = array_keys($found)[0];
        $value = $found[$key];

        foreach ($this->except['node'] as $idx => $item) {
            if (array_key_exists($key, $item)) {
                self::assertEquals($value, $item[$key]);
                unset($this->except['node'][$idx]);
                return;
            }
        }
    }

    public function tearDown(): void
    {
        $this->array = null;
        $this->except = null;
    }
}
