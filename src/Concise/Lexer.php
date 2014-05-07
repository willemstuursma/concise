<?php

namespace Concise;

class Lexer
{
	const TOKEN_KEYWORD = 1;

	const TOKEN_ATTRIBUTE = 2;

	const TOKEN_INTEGER = 3;

	const TOKEN_FLOAT = 4;

	const TOKEN_STRING = 5;

	// @test TOKEN_STRING with escaped quotes

	// @test TOKEN_STRING with escaped characters

	// @test keywords should be generated automatically from matchers
	protected static $keywords = array(
		'equal',
		'equals',
		'is',
		'not',
		'null',
		'to',
		'true'
	);

	protected static function isKeyword($token)
	{
		return in_array($token, self::$keywords);
	}

	public static function getTokenType($token)
	{
		if(self::isKeyword($token)) {
			return self::TOKEN_KEYWORD;
		}
		if(preg_match('/^[0-9]*\.[0-9]+$/', $token)) {
			return self::TOKEN_FLOAT;
		}
		if(preg_match('/^[0-9]+$/', $token)) {
			return self::TOKEN_INTEGER;
		}
		if(preg_match('/^".*"/', $token) || preg_match("/^'.*'/", $token)) {
			return self::TOKEN_STRING;
		}
		return self::TOKEN_ATTRIBUTE;
	}

	protected function consumeString($string, $container, $startIndex)
	{
		$t = '';
		for($i = $startIndex + 1; $i < strlen($string) && $string[$i] != $container; ++$i) {
			$t .= $string[$i];
		}
		return $t;
	}

	protected function getTokens($string)
	{
		// @test quotes string that is not closed
		$r = array();
		$t = '';
		for($i = 0; $i < strlen($string); ++$i) {
			$ch = $string[$i];
			if($ch == '"' || $ch == "'") {
				$t = $this->consumeString($string, $ch, $i);
				$r[] = new Token(Lexer::TOKEN_STRING, $t);
				$i += strlen($t) + 1;
				$t = '';
			}
			else if($ch == ' ') {
				if($t != '') {
					$r[] = new Token(self::getTokenType($t), $t);
				}
				$t = '';
			}
			else {
				$t .= $ch;
			}
		}
		if($t != '') {
			$r[] = new Token(self::getTokenType($t), $t);
		}
		return $r;
	}

	protected function getAttributes($string)
	{
		$tokens = $this->getTokens($string);
		$attributes = array();
		foreach($tokens as $token) {
			switch($token->getType()) {
				case self::TOKEN_KEYWORD:
					break;
				case self::TOKEN_INTEGER:
				case self::TOKEN_FLOAT:
					$attributes[] = $token->getValue() * 1;
					break;
				case self::TOKEN_STRING:
					$attributes[] = $token->getValue();
					break;
				case self::TOKEN_ATTRIBUTE:
				default:
					$attributes[] = new Attribute($token->getValue());
					break;
			}
		}
		return $attributes;
	}

	protected function getSyntax($string)
	{
		$tokens = $this->getTokens($string);
		$syntax = array();
		foreach($tokens as $token) {
			if($token->getType() !== self::TOKEN_KEYWORD) {
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
		return self::$keywords;
	}
}
