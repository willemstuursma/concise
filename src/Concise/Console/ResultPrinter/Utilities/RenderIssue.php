<?php

namespace Concise\Console\ResultPrinter\Utilities;

use Exception;
use Colors\Color;
use Concise\Console\Theme\DefaultTheme;
use PHPUnit_Framework_Test;

class RenderIssue
{
    protected $traceSimplifier;

    public function __construct(TraceSimplifier $traceSimplifier = null)
    {
        if (!$traceSimplifier) {
            $traceSimplifier = new TraceSimplifier();
        }
        $this->traceSimplifier = $traceSimplifier;
    }

    protected function prefixLines($prefix, $lines)
    {
        return $prefix . str_replace("\n", "\n$prefix", $lines);
    }

    public function render($status, $issueNumber, PHPUnit_Framework_Test $test, Exception $e)
    {
        $c = new Color();
        $theme = new DefaultTheme();
        $colors = $theme->getTheme();
        $top = "$issueNumber. " . get_class($test) . '::' . $test->getName() . "\n\n";
        $message = $e->getMessage() . "\n\n";
        $message .= $this->prefixLines("\033[90m", $this->traceSimplifier->render($e->getTrace())) . "\033[0m";
        $pad = str_repeat(' ', strlen($issueNumber));

        return $top . $this->prefixLines($c("  ")->highlight($colors[$status]) . $pad, rtrim($message));
    }
}
