<?php

namespace Concise\Mock;

class ArgumentMatcher
{
    public function match(array $a, array $b)
    {
        return count($a) === count($b);
    }
}
