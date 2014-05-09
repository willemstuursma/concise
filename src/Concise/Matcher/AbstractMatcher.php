<?php

namespace Concise\Matcher;

abstract class AbstractMatcher
{
	/**
	 * @return bool
	 */
	public abstract function match($syntax, array $data = array());

	/**
	 * @return array of syntaxes this matcher can understand.
	 */
	public abstract function supportedSyntaxes();

	/**
	 * @return string
	 */
	public function renderFailureMessage($syntax, array $data = array())
	{
		$renderer = new \Concise\SyntaxRenderer();
		return $renderer->render($syntax, $data);
	}
}
