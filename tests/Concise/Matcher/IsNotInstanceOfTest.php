<?php

namespace Concise\Matcher;

class IsNotInstanceOfTest extends AbstractMatcherTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->matcher = new IsNotInstanceOf();
    }

    public function testIsInstanceOfWithSameClass()
    {
        $this->assertFailure(new self(), is_not_an_instance_of, '\Concise\Matcher\IsNotInstanceOfTest');
    }

    public function testIsInstanceOfWithSuperClass()
    {
        $this->assertFailure(new self(), not_instance_of, '\Concise\Matcher\AbstractMatcherTestCase');
    }

    public function testIsInstanceOfFailure()
    {
        $this->assert(new \stdClass(), is_not_instance_of, '\Concise\Matcher\IsNotInstanceOfTest');
    }
}
