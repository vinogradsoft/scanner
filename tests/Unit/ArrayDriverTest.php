<?php

namespace Test\Unit;

use Test\Cases\Dummy\DummyNodeFactory;
use Vinograd\Scanner\ArrayDriver;
use PHPUnit\Framework\TestCase;

class ArrayDriverTest extends TestCase
{

    public function testConstruct()
    {
        $arrayDriver = new ArrayDriver($nodeFactory = new DummyNodeFactory());
        $reflection = new \ReflectionObject($arrayDriver);
        $property = $reflection->getProperty('nodeFactory');
        $property->setAccessible(true);
        $objectValue = $property->getValue($arrayDriver);
        self::assertSame($objectValue, $nodeFactory);
    }

    public function testGetNodeFactory()
    {
        $arrayDriver = new ArrayDriver($nodeFactory = new DummyNodeFactory());
        $factory = $arrayDriver->getNodeFactory();
        self::assertSame($factory, $nodeFactory);
    }

    public function testIsLeaf()
    {
        $arrayDriver = new ArrayDriver(new DummyNodeFactory());
        self::assertTrue($arrayDriver->isLeaf([5]));
        self::assertTrue($arrayDriver->isLeaf(['string']));
        self::assertFalse($arrayDriver->isLeaf([
            4 => [4]
        ]));
    }

    public function testIsLeafWithSnap()
    {
        $arrayDriver = new ArrayDriver(new DummyNodeFactory());
        $arrayDriver->isLeaf([5]);
        $reflection = new \ReflectionObject($arrayDriver);
        $property = $reflection->getProperty('dataForFilter');
        $property->setAccessible(true);
        $objectValue = $property->getValue($arrayDriver);
        self::assertEquals($objectValue, [0 => 5]);
    }

    public function testNormalise()
    {
        $arrayDriver = new ArrayDriver(new DummyNodeFactory());
        $result = $arrayDriver->normalise($control = ['value']);
        $result2 = $arrayDriver->normalise($control2 = 'string');
        self::assertEquals($result, $control);
        self::assertEquals($result2, [$control2]);
    }

    public function testGetDataFotFilter()
    {
        $arrayDriver = new ArrayDriver(new DummyNodeFactory());
        $arrayDriver->isLeaf([5]);
        self::assertEquals($arrayDriver->getDataForFilter(), [0 => 5]);
        $arrayDriver->isLeaf([[5]]);
        self::assertEquals($arrayDriver->getDataForFilter(), [0 => [5]]);
    }

    public function testParse()
    {
        $arrayDriver = new ArrayDriver(new DummyNodeFactory());
        $control = [
            [0 => 'zero'],
            [1 => 'zero'],
            [2 => 0],
            ['key0' => 'value'],
            ['key1' => 'value'],
            ['key2' => 'value2'],
            ['key3' => [4.5, 4]],
            ['key4' => [1, 3, 2]],
            ['key5' => [1, 3, 2]]
        ];
        $result = $arrayDriver->parse([
            'zero',
            'zero',
            0,
            'key0' => 'value',
            'key1' => 'value',
            'key2' => 'value2',
            'key3' => [4.5, 4],
            'key4' => [1, 3, 2],
            'key5' => [1, 3, 2]
        ]);

        self::assertEquals($control, $result);
        $control2 = [
            [0 => 1],
            [1 => 3],
            [2 => 2]
        ];

        $result2 = $arrayDriver->parse([1, 3, 2]);
        self::assertEquals($control2, $result2);
    }

    public function testInstallDependencyContext()
    {
        $arrayDriver = new ArrayDriver($factory = new DummyNodeFactory());
        $arrayDriver->beforeSearch();

        $reflection = new \ReflectionObject($arrayDriver);
        $property = $reflection->getProperty('nodeFactory');
        $property->setAccessible(true);
        $objectValue = $property->getValue($arrayDriver);
        self::assertSame($objectValue, $factory);

        $reflection = new \ReflectionObject($arrayDriver);
        $property = $reflection->getProperty('dataForFilter');
        $property->setAccessible(true);
        $objectValue = $property->getValue($arrayDriver);
        self::assertEmpty($objectValue);
    }

    public function testSetDetect()
    {
        $arrayDriver = new ArrayDriver($factory = new DummyNodeFactory());
        $arrayDriver->setDetect(['value']);

        $reflection = new \ReflectionObject($arrayDriver);
        $property = $reflection->getProperty('nodeFactory');
        $property->setAccessible(true);
        $objectValue = $property->getValue($arrayDriver);
        self::assertSame($objectValue, $factory);

        $reflection = new \ReflectionObject($arrayDriver);
        $property = $reflection->getProperty('dataForFilter');
        $property->setAccessible(true);
        $objectValue = $property->getValue($arrayDriver);
        self::assertEmpty($objectValue);
    }

}
