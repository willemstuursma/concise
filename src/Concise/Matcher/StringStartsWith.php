<?php

namespace Concise\Matcher;

use \Concise\Services\ToStringConverter;

class StringStartsWith extends AbstractMatcher
{
	public function supportedSyntaxes()
	{
		return array(
			'? starts with ?',
		);
	}

	public function match($syntax, array $data = array())
	{
		$converter = new ToStringConverter();
		$haystack = $converter->convertToString($data[0]);
		$needle = $converter->convertToString($data[1]);

		return ((substr($haystack, 0, strlen($needle)) === $needle));
	}
}