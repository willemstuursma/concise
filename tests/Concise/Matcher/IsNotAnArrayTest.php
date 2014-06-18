<?php

namespace Concise\Matcher;

class IsNotAnArrayTest extends AbstractMatcherTestCase
{
	public function setUp()
	{
		parent::setUp();
		$this->matcher = new IsNotAnArray();
	}

	public function testIsNotAnArray()
	{
		$this->assert('123 is not an array');
	}

	public function testIsNotAnArrayFailure()
	{
		$this->x = array();
		$this->assertFailure('x is not an array');
	}
}
