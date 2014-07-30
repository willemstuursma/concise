<?php

namespace Concise\Mock;

use \Concise\TestCase;
use \Concise\Mock\Action\ReturnValueAction;

class ClassCompilerMock3
{
	protected function hidden()
	{
		return 'foo';
	}

	protected function hidden2()
	{
		return 'foo';
	}
}

class ClassCompilerExposeTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();
		$this->compiler = new ClassCompiler('\Concise\Mock\ClassCompilerMock3', true);
	}

	public function testAMethodCanBeExposed()
	{
		$this->compiler->addExpose('hidden');
		$instance = $this->compiler->newInstance();
		$this->assert($instance->hidden(), equals, 'foo');
	}

	public function testAMethodThatHasARuleCanBeExposed()
	{
		$this->compiler->setRules(array('hidden' => array('action' => new ReturnValueAction('bar'))));
		$this->compiler->addExpose('hidden');
		$instance = $this->compiler->newInstance();
		$this->assert($instance->hidden(), equals, 'bar');
	}

	public function testAProtectedMethodMustStayProtected()
	{
		$instance = $this->compiler->newInstance();
		$reflectionClass = new \ReflectionClass(get_class($instance));
		$this->assert($reflectionClass->getMethod('hidden')->isPublic(), is_false);
	}

	public function testExposingOneMethodWillNotExposeThemAll()
	{
		$this->compiler->addExpose('hidden');
		$instance = $this->compiler->newInstance();
		$reflectionClass = new \ReflectionClass(get_class($instance));
		$this->assert($reflectionClass->getMethod('hidden2')->isPublic(), is_false);
	}

	public function testAddingARuleToAMethodWillNotExposeThemAll()
	{
		$this->compiler->setRules(array('hidden' => array('action' => new ReturnValueAction('bar'))));
		$instance = $this->compiler->newInstance();
		$reflectionClass = new \ReflectionClass(get_class($instance));
		$this->assert($reflectionClass->getMethod('hidden2')->isPublic(), is_false);
	}

	public function testAddingARuleToAMethodWillNotExposeIt()
	{
		$this->compiler->setRules(array('hidden' => array('action' => new ReturnValueAction('bar'))));
		$instance = $this->compiler->newInstance();
		$reflectionClass = new \ReflectionClass(get_class($instance));
		$this->assert($reflectionClass->getMethod('hidden')->isPublic(), is_false);
	}

	public function testMocksThatAreNotNiceWillNotExposeAMethod()
	{
		$this->compiler = new ClassCompiler('\Concise\Mock\ClassCompilerMock3');
		$instance = $this->compiler->newInstance();
		$reflectionClass = new \ReflectionClass(get_class($instance));
		$this->assert($reflectionClass->getMethod('hidden')->isPublic(), is_false);
	}

	public function testTwoMethodsCanBeExposed()
	{
		$this->compiler->addExpose('hidden');
		$this->compiler->addExpose('hidden2');
		$instance = $this->compiler->newInstance();
		$this->assert($instance->hidden(), equals, 'foo');
	}
}
