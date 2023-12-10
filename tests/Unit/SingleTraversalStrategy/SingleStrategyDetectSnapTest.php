<?php
declare(strict_types=1);

namespace Test\Unit\SingleTraversalStrategy;

use Test\Cases\Dummy\TestCaseProviderVisitor;
use Test\Cases\StrategyCase;
use Vinograd\Scanner\AbstractTraversalStrategy;
use Vinograd\Scanner\ArrayDriver;
use Vinograd\Scanner\SingleStrategy;
use Vinograd\Scanner\Verifier;

class SingleStrategyDetectSnapTest extends StrategyCase
{
    private $strategy;
    private $driver;
    private $detect;
    private $verifier1;
    private $verifier2;
    private $provider;

    public function setUp(): void
    {
        $this->provider = new TestCaseProviderVisitor($this);
        $this->strategy = new SingleStrategy();
        $this->driver = new ArrayDriver();
        $this->verifier1 = new Verifier();
        $this->verifier2 = new Verifier();
    }

    /**
     * @dataProvider getCase
     */
    public function testDetect($array)
    {
        $this->detect = $array;
        $this->strategy->detect($array, $this->driver, $this->verifier1, $this->verifier2, $this->provider);
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
                ]
            ],
            [//line2
                [
                    'key3' => [4.5, 4],
                    'key4' => [1, 3, 2]
                ]
            ],
            [//line3
                [
                    'zero',
                    'key1' => 'value',
                    'key2' => 'value2',
                ]
            ],
        ];
    }

    public function scanStarted(AbstractTraversalStrategy $scanStrategy, $detect): void
    {
        self::assertEquals($this->detect, $detect);
        self::assertEquals($this->strategy, $scanStrategy);
    }

    public function scanCompleted(AbstractTraversalStrategy $scanStrategy, $detect): void
    {
        self::assertEquals($this->detect, $detect);
        self::assertEquals($this->strategy, $scanStrategy);
    }

    public function visitLeaf(AbstractTraversalStrategy $scanStrategy, $detect, $found, $data = null): void
    {
        self::assertEmpty($data);
        self::assertEquals($this->detect, $detect);
        self::assertEquals($this->strategy, $scanStrategy);
    }

    public function visitNode(AbstractTraversalStrategy $scanStrategy, $detect, $found, $data = null): void
    {
        self::assertEmpty($data);
        self::assertEquals($this->detect, $detect);
        self::assertEquals($this->strategy, $scanStrategy);
    }

    public function tearDown(): void
    {
        $this->strategy = null;
        $this->driver = null;
        $this->detect = null;
        $this->verifier1 = null;
        $this->verifier2 = null;
    }
}
