<?php
/**
 * @package ilch_phpunit
 */

namespace Ilch;

use Ilch\Event as Event;
use PHPUnit\Ilch\TestCase;

/**
 * Tests the event object.
 *
 * @package ilch_phpunit
 */
class EventTest extends TestCase
{
    /**
    * Initialize Event with name 'testEvent' and check if name is testEvent
    * and arguments an empty array.
    *
    */
    public function testInitializeEvent()
    {
        $event = new Event('testEvent');

        $this->assertTrue($event->getName() == 'testEvent');
        $this->assertTrue(count($event->getArguments()) == 0);
    }

    /**
    * Initialize Event with name 'testEvent' and an array
    * and check if the argument array contains the expected number of objects.
    *
    */
    public function testInitializeEventArray()
    {
        $event = new Event('testEvent', ['a',1]);

        $this->assertTrue($event->getName() == 'testEvent');
        $this->assertTrue(count($event->getArguments()) == 2);
    }

    /**
    * Initialize Event with name 'testEvent' and an array
    * and check if getArgument returns the expected values.
    *
    */
    public function testGetArgument()
    {
        $event = new Event('testEvent', ['a' => 'aa', 'b' => 1]);
        
        $this->assertTrue($event->getArgument('a') == 'aa');
        $this->assertTrue($event->getArgument('b') == 1);
    }

    /**
    * Check if an invalid key results in the expected exception.
    * @expectedException InvalidArgumentException
    *
    */
    public function testGetArgumentException()
    {
        $event = new Event('testEvent', []);
        
        $this->assertTrue($event->getArgument('a') == 'aa');
    }

    /**
    * Initialize Event with name 'testEvent' and an array
    * and check if hasArgument returns true for a valid key.
    *
    */
    public function testHasArgument()
    {
        $event = new Event('testEvent', ['a' => 'aa']);
        
        $this->assertTrue($event->hasArgument('a'));
    }

    /**
    * Initialize Event with name 'testEvent' and an array
    * and check if hasArgument returns false for an invalid key.
    *
    */
    public function testHasArgumentInvalidKey()
    {
        $event = new Event('testEvent', ['a' => 'aa']);
        
        $this->assertFalse($event->hasArgument('b'));
    }

    /**
    * Call setArgument to add an argument and check if getArguments returns
    * the expected value.
    *
    */
    public function testSetArgument()
    {
        $event = new Event('testEvent');
        
        $event->setArgument('a', 'aa');
        $this->assertTrue($event->getArgument('a') == 'aa');
    }

    /**
    * Call setArguments to add an array of arguments and check if getArgument returns
    * the expected values.
    *
    */
    public function testSetArguments()
    {
        $event = new Event('testEvent');
        
        $event->setArguments(['a' => 'aa', 'b' => 1]);
        $this->assertTrue($event->getArgument('a') == 'aa');
        $this->assertTrue($event->getArgument('b') == 1);
    }
}
