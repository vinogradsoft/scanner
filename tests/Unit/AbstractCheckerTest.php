<?php
declare(strict_types=1);

namespace Test\Unit;

use Vinograd\Scanner\AbstractChecker;
use PHPUnit\Framework\TestCase;
use Vinograd\Scanner\Checker;

class AbstractCheckerTest extends TestCase
{
    public function testAppend()
    {
        $abstractChecker = $this->getMockForAbstractClass(AbstractChecker::class);
        $next = $abstractChecker->append($checker = new class() implements Checker {

            public function append(Checker $checker): Checker
            {
                return $checker;
            }

            public function can($found): bool
            {
                return true;
            }
        });
        $reflection = new \ReflectionObject($abstractChecker);
        $property = $reflection->getProperty('next');
        $property->setAccessible(true);
        $nextObjectValue = $property->getValue($abstractChecker);
        self::assertSame($next, $checker);
        self::assertSame($nextObjectValue, $checker);
    }

    public function testCan()
    {
        $abstractChecker = $this->getMockForAbstractClass(AbstractChecker::class);
        $abstractChecker->append(new class() implements Checker {

            public function append(Checker $checker): Checker
            {
                return $checker;
            }

            public function can($found): bool
            {
                return $found === 'assert';
            }
        });
        self::assertTrue($abstractChecker->can('assert'));
        self::assertFalse($abstractChecker->can('not'));
    }

    public function testCanEmptyNext()
    {
        $abstractChecker = $this->getMockForAbstractClass(AbstractChecker::class);
        self::assertTrue($abstractChecker->can('assert'));
        self::assertTrue($abstractChecker->can('not'));
    }
}
