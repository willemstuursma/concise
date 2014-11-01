<?php

namespace Concise\Matcher;

class DoesNotContainStringTest extends AbstractMatcherTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->matcher = new DoesNotContainString();
    }

    public function testSuccessIfStringContainsASubstring()
    {
        $this->assertFailure('foobar', does_not_contain_string, 'oob');
    }

    public function testFailsIfSubstringDoesNotExistInString()
    {
        $this->assert('foobar', does_not_contain_string, 'baz');
    }

    public function testIsSensitiveToCase()
    {
        $this->assert('foobar', does_not_contain_string, 'Foo');
    }

    public function tags()
    {
        return array(Tag::STRINGS);
    }
}
