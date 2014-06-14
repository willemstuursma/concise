<?php

namespace Concise\Matcher;

use \Concise\TestCase;

class MatchesRegularExpressionTest extends AbstractMatcherTestCase
{
	public function setUp()
	{
		parent::setUp();
		$this->matcher = new MatchesRegularExpression();
	}

	public function testMatchesRegularExpression()
	{
		$this->assert('123 matches regular expression /\\d+/');
	}
}
