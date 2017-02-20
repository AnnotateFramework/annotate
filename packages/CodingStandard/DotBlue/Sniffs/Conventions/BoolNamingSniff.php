<?php

namespace DotBlue\Sniffs\Conventions;

use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Sniff;


class BoolNamingSniff implements PHP_CodeSniffer_Sniff
{

	public function register()
	{
		return [
			T_DOC_COMMENT_STRING,
		];
	}



	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();
		$content = $tokens[$stackPtr]['content'];
		if (preg_match('/boolean/', $content)) {
			$fix = $phpcsFile->addFixableError('Usage of "boolean" is forbidden. Use "bool" instead.', $stackPtr);

			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->replaceToken($stackPtr, str_replace('boolean', 'bool', $content));
				$phpcsFile->fixer->endChangeset();
			}
		}
	}

}
