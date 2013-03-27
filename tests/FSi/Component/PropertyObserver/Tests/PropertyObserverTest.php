<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\PropertyObserver\Tests;

use FSi\Component\PropertyObserver\PropertyObserver;

class PropertyObserverTest extends \PHPUnit_Framework_TestCase
{
    public function testValueChanged()
    {
        $observer = PropertyObserver::getPropertyObserver();

        $object = new TestObject();
        $object->property1 = 'original value 1';
        $object->property2 = 'original value 2';

        $observer->saveValue($object, 'property1');
        $observer->saveValue($object, 'property2');
        $observer->saveValue($object, 'property3');

        $object->property1 = 'new value 1';
        $object->property3 = 'new value 3';
        $this->assertTrue($observer->hasValueChanged($object, 'property1'));
        $this->assertFalse($observer->hasValueChanged($object, 'property2'));
        $this->assertTrue($observer->hasValueChanged($object, 'property3'));
        $this->setExpectedException('BadMethodCallException');
        $observer->hasValueChanged($object, 'property4');
    }

    public function testGetSavedValue()
    {
        $observer = PropertyObserver::getPropertyObserver();

        $object = new TestObject();
        $object->property1 = 'original value 1';
        $object->property2 = 'original value 2';

        $observer->saveValue($object, 'property1');
        $observer->saveValue($object, 'property2');
        $observer->saveValue($object, 'property3');

        $object->property1 = 'new value 1';
        $object->property3 = 'new value 3';
        $this->assertEquals(
            $observer->getSavedValue($object, 'property1'),
            'original value 1'
        );
        $this->assertEquals(
            $observer->getSavedValue($object, 'property2'),
            'original value 2'
        );
        $this->assertNull($observer->getSavedValue($object, 'property3'));
        $this->setExpectedException('BadMethodCallException');
        $observer->getSavedValue($object, 'property4');
    }
}

class TestObject
{
    public $property1;

    public $property2;

    public $property3;

    public $property4;
}
