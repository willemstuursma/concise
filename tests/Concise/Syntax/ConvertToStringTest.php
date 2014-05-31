<?php

namespace Concise\Syntax;

class ConvertToStringTest extends \Concise\TestCase
{
	public function prepare()
	{
		parent::prepare();
		$this->converter = new ConvertToString();
	}

	/**
	 * @expectedException \Exception
	 * @expectedExceptionMessage Cannot convert boolean to string.
	 */
	public function testWillThrowExceptionIfABooleanTrueValueIsUsed()
	{
		$this->converter->convertToString(true);
	}

	public function testWillReturnTheExactStringUsed()
	{
		$this->assertSame($this->converter->convertToString('hello'), 'hello');
	}

	/**
	 * @expectedException \Exception
	 * @expectedExceptionMessage Cannot convert boolean to string.
	 */
	public function testWillThrowExceptionIfABooleanFalseValueIsUsed()
	{
		$this->converter->convertToString(false);
	}

	public function testWillConvertANumberToAString()
	{
		$this->assertSame($this->converter->convertToString(123), '123');
	}

	public function testWillReturnTheMethodsReturnValueIfItIsCallable()
	{
		$this->assertSame($this->converter->convertToString(function () {
			return 'abc';
		}), 'abc');
	}

	public function testWillAlwaysReturnAStringEvenIfItHasToRecurse()
	{
		$this->assertSame($this->converter->convertToString(function () {
			return function() {
				return 123;
			};
		}), '123');
	}

	/**
	 * @expectedException \Exception
	 * @expectedExceptionMessage Cannot convert NULL to string.
	 */
	public function testWillThrowExceptionIfANullValueIsUsed()
	{
		$this->converter->convertToString(null);
	}

	public function testWillReturnTheExceptionMessageIfTheCallableValueThrowsAnException()
	{
		$this->assertSame($this->converter->convertToString(function () {
			throw new \Exception('hi');
		}), 'hi');
	}

	public function testWillRenderAnObjectAsAString()
	{
		$object = $this->getStub('\stdClass', array(
			'__toString' => 'xyz'
		));
		$this->assertSame($this->converter->convertToString($object), 'xyz');
	}

	public function testWillExpandScientificNotationToAbsoluteValue()
	{
		$this->assertSame($this->converter->convertToString(1.23e5), '123000');
	}

	/**
	 * @expectedException \Exception
	 * @expectedExceptionMessage Object of class stdClass could not be converted to string
	 */
	public function testWillThrowExceptionIfAnObjectThatCanNotBeConvertedToAString()
	{
		$object = new \stdClass();
		$this->converter->convertToString($object);
	}

	public function testWillReturnAJsonStringForAnArray()
	{
		$this->assertSame($this->converter->convertToString(array(1, 'abc')), '[1,"abc"]');
	}
}
