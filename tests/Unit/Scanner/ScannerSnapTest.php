<?php

namespace Test\Unit\Scanner;

use Test\Cases\Dummy\DummyNodeFactory;
use Test\Cases\Dummy\DummyVisitor;
use Vinograd\Scanner\AbstractTraversalStrategy;
use Vinograd\Scanner\ArrayDriver;
use Vinograd\Scanner\Driver;
use Vinograd\Scanner\Exception\ConfigurationException;
use Vinograd\Scanner\Scanner;
use PHPUnit\Framework\TestCase;
use Vinograd\Scanner\SingleStrategy;
use Vinograd\Scanner\Verifier;

class ScannerSnapTest extends TestCase
{
    public function testConstruct()
    {
        $scanner = new Scanner();

        $reflection = new \ReflectionObject($scanner);
        $property = $reflection->getProperty('driver');
        $property->setAccessible(true);
        $objectValue = $property->getValue($scanner);
        self::assertEmpty($objectValue);

        $reflection = new \ReflectionObject($scanner);
        $property = $reflection->getProperty('leafVerifier');
        $property->setAccessible(true);
        $objectValue = $property->getValue($scanner);
        self::assertInstanceOf(Verifier::class, $objectValue);
        self::assertNotEmpty($objectValue);

        $reflection = new \ReflectionObject($scanner);
        $property = $reflection->getProperty('nodeVerifier');
        $property->setAccessible(true);
        $objectValue = $property->getValue($scanner);
        self::assertInstanceOf(Verifier::class, $objectValue);
        self::assertNotEmpty($objectValue);

        $reflection = new \ReflectionObject($scanner);
        $property = $reflection->getProperty('visitor');
        $property->setAccessible(true);
        $objectValue = $property->getValue($scanner);
        self::assertEmpty($objectValue);

        $reflection = new \ReflectionObject($scanner);
        $property = $reflection->getProperty('traversal');
        $property->setAccessible(true);
        $objectValue = $property->getValue($scanner);
        self::assertEmpty($objectValue);
    }

    public function testSetStrategy()
    {
        $scanner = new Scanner();
        $scanner->setStrategy($strategy = $this->getMockForAbstractClass(AbstractTraversalStrategy::class));
        self::assertSame($strategy, $scanner->getStrategy());
        $scanner->setStrategy($strategy);
        self::assertSame($strategy, $scanner->getStrategy());
    }

    public function testSetVisitor()
    {
        $scanner = new Scanner();
        $visitor = new DummyVisitor();

        $scanner->setVisitor($visitor);
        self::assertSame($visitor, $scanner->getVisitor());

        $scanner->setVisitor($visitor);
        self::assertSame($visitor, $scanner->getVisitor());

        $scanner->setVisitor($visitor1 = new DummyVisitor());
        self::assertSame($visitor1, $scanner->getVisitor());
    }

    public function testSetDriver()
    {
        $scanner = new Scanner();
        $scanner->setDriver($driver = new ArrayDriver(new DummyNodeFactory()));
        self::assertSame($driver, $scanner->getDriver());
    }

    public function testSearchNoDriver()
    {
        $this->expectException(ConfigurationException::class);
        $scanner = new Scanner();
        $scanner->setStrategy(new SingleStrategy());
        $scanner->setVisitor(new DummyVisitor());
        $scanner->search([]);
    }

    public function testSearchNoStrategy()
    {
        $this->expectException(ConfigurationException::class);
        $scanner = new Scanner();
        $scanner->setDriver($this->getMockForAbstractClass(Driver::class));
        $scanner->setVisitor(new DummyVisitor());
        $scanner->search([]);
    }

    public function testSearchNoVisitor()
    {
        $this->expectException(ConfigurationException::class);
        $scanner = new Scanner();
        $scanner->setDriver($this->getMockForAbstractClass(Driver::class));
        $scanner->setStrategy(new SingleStrategy());
        $scanner->search([]);
    }
}
