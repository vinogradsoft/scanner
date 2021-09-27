<?php

namespace Test\Unit;

use Vinograd\Scanner\Filter;
use Vinograd\Scanner\Leaf;
use Vinograd\Scanner\Verifier;
use PHPUnit\Framework\TestCase;

class VerifierTest extends TestCase
{

    public function testAppend()
    {
        $verifier = new Verifier();
        $verifier->append(new class() implements Filter {
            public function filter($node): bool
            {
                $pathInfo = pathinfo($node->getSource());
                return 'php' === $pathInfo['extension'];
            }

            public function setConfiguration($config): void
            {
            }
        })->append(new class() implements Filter {
            public function filter($node): bool
            {
                $sub = substr($node->getSource(), 0, 4);
                return $sub === 'conf';
            }

            public function setConfiguration($config): void
            {
            }
        })->append(new class() implements Filter {
            public function filter($node): bool
            {
                return $node->getSource() === 'conftest1.php';
            }

            public function setConfiguration($config): void
            {
            }
        });

        $coolFile = $this->createStub(Leaf::class);
        $coolFile->method('getSource')
            ->willReturn('conftest1.php');


        $badFile = $this->createStub(Leaf::class);
        $badFile->method('getSource')
            ->willReturn('conftest1.yml');

        $badFile2 = $this->createStub(Leaf::class);
        $badFile2->method('getSource')
            ->willReturn('contest1.yml');

        self::assertTrue($verifier->can($coolFile));
        self::assertFalse($verifier->can($badFile));
        self::assertFalse($verifier->can($badFile2));

        $verifier2 = new Verifier();
        $verifier2->append(new class() implements Filter {
            public function filter($node): bool
            {
                $pathInfo = pathinfo($node->getSource());
                return 'php' === $pathInfo['extension'];
            }

            public function setConfiguration($config): void
            {
            }
        })->append(new class() implements \Vinograd\Scanner\Filter {
            public function filter($node): bool
            {
                $sub = substr($node->getSource(), 0, 4);
                return $sub === 'conf';
            }

            public function setConfiguration($config): void
            {
            }
        });

        $coolFile2 = $this->createStub(Leaf::class);
        $coolFile2->method('getSource')
            ->willReturn('conf.php');

        $badFile3 = $this->createStub(Leaf::class);
        $badFile3->method('getSource')
            ->willReturn('conf.dhp');

        self::assertTrue($verifier2->can($coolFile2));
        self::assertFalse($verifier2->can($badFile3));

        $verifier3 = new Verifier();//фильтров нет, вернул true
        self::assertTrue($verifier3->can($coolFile2));
        self::assertTrue($verifier3->can($badFile3));
    }

    public function testClear()
    {
        $verifier = new Verifier();
        $verifier->append(new class() implements Filter {
            public function filter($node): bool
            {
                $pathInfo = pathinfo($node->getSource());
                return 'php' === $pathInfo['extension'];
            }

            public function setConfiguration($config): void
            {
            }
        })->append(new class() implements Filter {
            public function filter($node): bool
            {
                $sub = substr($node->getSource(), 0, 4);
                return $sub === 'conf';
            }

            public function setConfiguration($config): void
            {
            }
        })->append(new class() implements Filter {
            public function filter($node): bool
            {
                return $node->getSource() === 'conftest1.php';
            }

            public function setConfiguration($config): void
            {
            }
        });
        $verifier->clear();

        $reflection = new \ReflectionObject($verifier);
        $property = $reflection->getProperty('initialChecker');
        $property->setAccessible(true);
        $objectValue = $property->getValue($verifier);
        self::assertEmpty($objectValue);
        $reflection = new \ReflectionObject($verifier);
        $property = $reflection->getProperty('checker');
        $property->setAccessible(true);
        $objectValue = $property->getValue($verifier);
        self::assertEmpty($objectValue);
    }
}
