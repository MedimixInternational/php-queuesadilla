<?php

namespace josegonzalez\Queuesadilla\Engine;

use \josegonzalez\Queuesadilla\Engine\TestEngine;
use \PHPUnit_Framework_TestCase;

class TestEngineTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->Engine = new TestEngine(array(
            'queue' => 'default',
        ));
    }

    public function tearDown()
    {
        unset($this->Engine);
    }

    /**
     * @covers josegonzalez\Queuesadilla\Engine\Base::__construct
     * @covers josegonzalez\Queuesadilla\Engine\Base::connected
     */
    public function testConstruct()
    {
        $Engine = new TestEngine(array());
        $this->assertTrue($Engine->connected());
    }

    /**
     * @covers josegonzalez\Queuesadilla\Engine\Base::bulk
     */
    public function testBulk()
    {
        $this->assertEquals(array(true, true), $this->Engine->bulk(array(null, null)));

        $this->Engine->return = false;
        $this->assertEquals(array(false, false), $this->Engine->bulk(array(null, null)));
    }

    /**
     * @covers josegonzalez\Queuesadilla\Engine\Base::getJobClass
     */
    public function testGetJobClass()
    {
        $this->assertEquals('\\josegonzalez\\Queuesadilla\\Job', $this->Engine->getJobClass());
    }

    /**
     * @covers josegonzalez\Queuesadilla\Engine\Base::setting
     */
    public function testSetting()
    {
        $this->assertEquals('string_to_array', $this->Engine->setting('string_to_array', 'queue'));
        $this->assertEquals('non_default', $this->Engine->setting(array('queue' => 'non_default'), 'queue'));
        $this->assertEquals('default', $this->Engine->setting(array(), 'queue'));
        $this->assertEquals('other', $this->Engine->setting(array(), 'other', 'other'));
    }

    /**
     * @covers josegonzalez\Queuesadilla\Engine\Base::watch
     */
    public function testWatch()
    {
        $this->assertTrue($this->Engine->watch('non_default'));
        $this->assertTrue($this->Engine->watch('other'));
        $this->assertTrue($this->Engine->watch());
    }

    /**
     * @covers josegonzalez\Queuesadilla\Engine\Base::connect
     * @covers josegonzalez\Queuesadilla\Engine\TestEngine::connect
     */
    public function testConnect()
    {
        $this->assertTrue($this->Engine->connect());

        $this->Engine->return = false;
        $this->assertFalse($this->Engine->connect());
    }

    /**
     * @covers josegonzalez\Queuesadilla\Engine\Base::delete
     * @covers josegonzalez\Queuesadilla\Engine\TestEngine::delete
     */
    public function testDelete()
    {
        $this->assertTrue($this->Engine->delete(null));

        $this->Engine->return = false;
        $this->assertFalse($this->Engine->delete(null));
    }

    /**
     * @covers josegonzalez\Queuesadilla\Engine\Base::pop
     * @covers josegonzalez\Queuesadilla\Engine\TestEngine::pop
     */
    public function testPop()
    {
        $this->assertTrue($this->Engine->pop('default'));

        $this->Engine->return = false;
        $this->assertFalse($this->Engine->pop('default'));
    }

    /**
     * @covers josegonzalez\Queuesadilla\Engine\Base::push
     * @covers josegonzalez\Queuesadilla\Engine\TestEngine::push
     */
    public function testPush()
    {
        $this->assertTrue($this->Engine->push(null, array(), 'default'));

        $this->Engine->return = false;
        $this->assertFalse($this->Engine->connect(null, array(), 'default'));
    }

    /**
     * @covers josegonzalez\Queuesadilla\Engine\Base::release
     * @covers josegonzalez\Queuesadilla\Engine\TestEngine::release
     */
    public function testRelease()
    {
        $this->assertTrue($this->Engine->release(null, 'default'));

        $this->Engine->return = false;
        $this->assertFalse($this->Engine->release(null, 'default'));
    }

    /**
     * @covers josegonzalez\Queuesadilla\Engine\Base::jobId
     */
    public function testId()
    {
        $this->assertInternalType('int', $this->Engine->jobId());
        $this->assertInternalType('int', $this->Engine->jobId());
        $this->assertInternalType('int', $this->Engine->jobId());
    }
}