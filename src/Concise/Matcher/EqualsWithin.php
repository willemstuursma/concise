<?php

namespace Concise\Matcher;

class EqualsWithin extends AbstractMatcher
{
    public function supportedSyntaxes()
    {
        return array(
            '? equals ? within ?' => 'Assert two values are close to each other.',
        );
    }

    public function match($syntax, array $data = array())
    {
        return true;
    }

    public function getTags()
    {
        return array(Tag::NUMBERS);
    }
}
