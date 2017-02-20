<?php

namespace DotBlue\Sniffs\Php;

use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Sniff;


class ForceEmptyConstructorParenthesesSniff implements PHP_CodeSniffer_Sniff
{

	public function register()
	{
		return [
			T_NEW,
		];
	}



	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		$end = $phpcsFile->findNext([
			T_CLOSE_PARENTHESIS,
			T_COMMA,
			T_SEMICOLON,
		], $stackPtr);

		$hasParentheses = $phpcsFile->findNext(T_OPEN_PARENTHESIS, $stackPtr, $end);

		if (!$hasParentheses) {
			$fix = $phpcsFile->addFixableError('There must be parentheses after constructor call.', $stackPtr);

			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->addContentBefore($end, '()');
				$phpcsFile->fixer->endChangeset();
			}
		}
	}

}
