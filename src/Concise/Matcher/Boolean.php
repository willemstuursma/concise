<?php

namespace Concise\Matcher;

class Boolean extends AbstractMatcher
{
	public function supportedSyntaxes()
	{
		return array(
			'? is false'
		);
	}

	public function match($syntax, array $data = array())
	{
		return $this->getComparer()->compare($data[0], false);
	}
}
