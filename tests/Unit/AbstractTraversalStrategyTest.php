<?php

namespace Test\Unit;

use Vinograd\Scanner\AbstractTraversalStrategy;
use PHPUnit\Framework\TestCase;

class AbstractTraversalStrategyTest extends TestCase
{

    public function testIsStop()
    {
        $abstractTraversalStrategy = $this->getMockForAbstractClass(AbstractTraversalStrategy::class);
        self::assertFalse($abstractTraversalStrategy->isStop());
        $abstractTraversalStrategy->setStop(true);
        self::assertTrue($abstractTraversalStrategy->isStop());
        $abstractTraversalStrategy->setStop(false);
        self::assertFalse($abstractTraversalStrategy->isStop());
    }
}
