<?php
declare(strict_types=1);

namespace Test\Unit\SingleTraversalStrategy;

use Test\Cases\Dummy\DummyNodeFactory;
use Test\Cases\Dummy\TestCaseProviderVisitor;
use Test\Cases\StrategyCase;
use Vinograd\Scanner\AbstractTraversalStrategy;
use Vinograd\Scanner\ArrayDriver;
use Vinograd\Scanner\NodeFactory;
use Vinograd\Scanner\SingleStrategy;
use Vinograd\Scanner\Verifier;

class SingleStrategyDetectWithStopTest extends StrategyCase
{
    private $strategy;
    private $driver;
    private $found;
    private $provider;

    public function setUp(): void
    {
        $this->provider = new TestCaseProviderVisitor($this);
        $this->strategy = new SingleStrategy();
        $this->driver = new ArrayDriver();
    }

    /**
     * @dataProvider getCase
     */
    public function testDetect($array, $found)
    {
        $this->found = $found;
        $verifier = new Verifier();
        $this->strategy->detect( $array, $this->driver,new DummyNodeFactory(), $verifier, $verifier,$this->provider);
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
                'zero'
            ],
            [//line2
                [
                    'key3' => [4.5, 4],
                    'key4' => [1, 3, 2]
                ],
                'key4'
            ],
            [//line3
                [
                    'zero',
                    'key1' => 'value',
                    'key2' => 'value2',
                ],
                'value'
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
        if ($this->found == $value) {
            self::assertTrue(true);
            $scanStrategy->setStop(true);
        }
    }

    public function visitNode(AbstractTraversalStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {
        self::assertCount(1, $found);
        $key = array_keys($found)[0];

        if ($this->found == $key) {
            self::assertTrue(true);
            $scanStrategy->setStop(true);
        }
    }

}
