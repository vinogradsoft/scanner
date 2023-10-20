<?php
declare(strict_types=1);

namespace Test\Unit;

use Vinograd\Scanner\BaseChecker;
use PHPUnit\Framework\TestCase;
use Vinograd\Scanner\Filter;

class BaseCheckerTest extends TestCase
{

    public function testCan()
    {
        $call1 = new class() implements Filter {
            public function filter($node): bool
            {
                $pathInfo = pathinfo($node);
                return 'php' === $pathInfo['extension'];
            }

            public function setConfiguration($config): void
            {
            }
        };

        $call2 = new class() implements \Vinograd\Scanner\Filter {
            public function filter($node): bool
            {
                $sub = substr($node, 0, 4);
                return $sub === 'conf';
            }

            public function setConfiguration($config): void
            {
            }
        };

        $call3 = new class() implements \Vinograd\Scanner\Filter {
            public function filter($node): bool
            {
                return $node === 'conftest1.php';
            }

            public function setConfiguration($config): void
            {
            }
        };

        $coolFile = 'conftest1.php';
        $badFile = 'conftest1.yml';

        $check = new \Vinograd\Scanner\BaseChecker($call1);

        $check->append(new \Vinograd\Scanner\BaseChecker($call2))->append(new \Vinograd\Scanner\BaseChecker($call3));

        self::assertEquals(true, $check->can($coolFile));
        self::assertEquals(false, $check->can($badFile));

        $check2 = new \Vinograd\Scanner\BaseChecker($call1);
        $check2->append(new \Vinograd\Scanner\BaseChecker($call2));
        $coolFile2 = 'conf_tedsghdghdfgh.php';

        self::assertEquals(true, $check2->can($coolFile));
        self::assertEquals(false, $check2->can($badFile));
        self::assertEquals(true, $check2->can($coolFile2));

    }
}
