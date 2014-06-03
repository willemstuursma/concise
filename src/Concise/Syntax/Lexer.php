<?php

namespace Concise\Syntax;

use \Concise\Services\CharacterConverter;

class Lexer
{
	protected static function isKeyword($token)
	{
		return in_array($token, self::getKeywords());
	}

	protected function consumeUntilToken($string, $until, &$startIndex, $mustConsumeUntil = true)
	{
		$t = '';
		for($i = $startIndex + 1; $string[$i] != $until; ++$i) {
			if($i == strlen($string) - 1) {
				if($mustConsumeUntil) {
					throw new \Exception("Expected $until before end of string.");
				}
				$t .= $string[$i];
				break;
			}
			if(($until === "'" || $until === '"') && $string[$i] === "\\") {
				++$i;
				$converter = new CharacterConverter();
				$t .= $converter->convertEscapedCharacter($string[$i]);
			}
			else {
				$t .= $string[$i];
			}
		}
		$startIndex = $i;
		return $t;
	}

	protected function consumeString($string, $container, &$startIndex)
	{
		return $this->consumeUntilToken($string, $container, $startIndex);
	}

	protected function consumeClassname($string, &$startIndex)
	{
		return $this->consumeUntilToken($string, ' ', $startIndex, false);
	}

	protected function consumeCode($string, &$startIndex)
	{
		return $this->consumeUntilToken($string, '`', $startIndex);
	}

	protected function consumeRegexp($string, &$startIndex)
	{
		return $this->consumeUntilToken($string, '/', $startIndex);
	}

	protected function translateValue($t)
	{
		if(self::isKeyword($t)) {
			return new Token\Keyword($t);
		}
		if(preg_match('/^\-?[0-9]*\.[0-9]+([eE][\-+]?[0-9]+)?$/', $t) ||
			preg_match('/^\-?[0-9]+([eE][\-+]?[0-9]+)?$/', $t)) {
			return new Token\Value($t * 1);
		}
		return new Token\Attribute($t);
	}

	protected function getTokens($string)
	{
		$r = array();
		$t = '';
		for($i = 0; $i < strlen($string); ++$i) {
			$ch = $string[$i];
			if($ch === '"' || $ch === "'") {
				$t = $this->consumeString($string, $ch, $i);
				$r[] = new Token\Value($t);
				$t = '';
			}
			else if($ch === "\\") {
				$t = $this->consumeClassname($string, $i);
				$r[] = new Token\Value($t);
				$t = '';
			}
			else if($ch === '`') {
				$t = $this->consumeCode($string, $i);
				$r[] = new Token\Code($t);
				$t = '';
			}
			else if($ch === '/') {
				$t = $this->consumeRegexp($string, $i);
				$r[] = new Token\Regexp($t);
				$t = '';
			}
			else if($ch === ' ') {
				if($t !== '') {
					$r[] = $this->translateValue($t);
					$t = '';
				}
			}
			else {
				$t .= $ch;
			}
		}
		if($t !== '') {
			$r[] = $this->translateValue($t);
		}
		return $r;
	}

	protected function getAttributes($string)
	{
		$tokens = $this->getTokens($string);
		$attributes = array();
		foreach($tokens as $token) {
			if($token instanceof Token\Keyword) {
				continue;
			}
			if($token instanceof Token\Value) {
				$attributes[] = $token->getValue();
			}
			else {
				$attributes[] = $token;
			}
		}
		return $attributes;
	}

	protected function getSyntax($string)
	{
		$tokens = $this->getTokens($string);
		$syntax = array();
		foreach($tokens as $token) {
			if(!($token instanceof Token\Keyword)) {
				$syntax[] = '?';
			}
			else {
				$syntax[] = $token->getValue();
			}
		}
		return implode(' ', $syntax);
	}

	public function parse($string)
	{
		return array(
			'tokens' => $this->getTokens($string),
			'syntax' => $this->getSyntax($string),
			'arguments' => $this->getAttributes($string)
		);
	}

	public static function getKeywords()
	{
		return MatcherParser::getInstance()->getKeywords();
	}
}
